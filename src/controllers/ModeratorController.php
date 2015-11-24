<?php

namespace sourcetoad\bbii2\controllers;

use sourcetoad\bbii2\components\BbiiController;
use sourcetoad\bbii2\models\BbiiForum;
use sourcetoad\bbii2\models\BbiiIpaddress;
use sourcetoad\bbii2\models\BbiiMessage;
use sourcetoad\bbii2\models\BbiiPost;
use sourcetoad\bbii2\models\BbiiTopic;
use sourcetoad\bbii2\models\MailForm;

use Yii;
use yii\filters\AccessControl;

class ModeratorController extends BbiiController {
    
    /**
     *
     * @deprecated 3.0.6fd6d72
     * @return array action filters
     */
    public function filters() {
        return false;
        /* return array(
            'accessControl',
        ); */
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @deprecated 3.0.6fd6d72
     * @return array access control rules
     */
    public function accessRules() {
        return false;
        /* return array(
            array('allow',
                'actions' => array('admin','approval','approve','banIp','changeTopic','delete','ipAdmin','ipDelete','view','refreshTopics','report','topic','sendmail'),
                'users' => array('@'),
                'expression' => ($this->isModerator())?'true':'false',
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        ); */
    }

    /**
     * Yii2 simple RBAL ACL
     *
     * @version  3.0.6fd6d72
     * @since 3.0.6fd6d72
     * @return array
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'       => array('admin','approval','approve','banIp','changeTopic','delete','ipadmin','ipdelete','view','refreshtopics','report','topic','sendmail'),
                        'allow'         => true,
                        'matchCallback' => function() { return $this->isModerator(); },
                    ],
                ],
            ],
        ];
    }

    public function actionApproval() {
        //$model = new BbiiPost('search');
        // $model->unsetAttributes();  // clear any default values
        $model = new BbiiPost();
        if (isset(\Yii::$app->request->get()['BbiiMessage'])) {
            $model->load(\Yii::$app->request->get()['BbiiPost']);
        }
        // restrict filtering to unapproved posts
        $model->approved = 0;

        return $this->render('approval', array(
            'model' => $model, 
        ));
    }
    
    public function actionApprove($id) {
        $post = BbiiPost::find($id);
        if ($post === null) {
            throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
        }
        $forum = BbiiForum::find($post->forum_id);
        $topic = BbiiTopic::find($post->topic_id);
        if ($topic->approved == 0) {
            $topic->approved = 1;
            $topic->update();
            $forum->updateCounters(array('num_topics' => 1));    // method since Yii 1.1.8
        } else {
            $topic->updateCounters(array('num_replies' => 1));                // method since Yii 1.1.8
        }
        $topic->saveAttributes(array('last_post_id' => $post->id));
        $post->approved = 1;
        $post->update();
        $this->resetLastForumPost($forum->id);
        $forum->updateCounters(array('num_posts' => 1));        // method since Yii 1.1.8
        $post->poster->updateCounters(array('posts' => 1));        // method since Yii 1.1.8
        $this->assignMembergroup($post->user_id);
        
        return \Yii::$app->response->redirect(array('forum/approval'));
    }
    
    public function actionAdmin() {
        //$model = new BbiiPost('search');
        // $model->unsetAttributes();  // clear any default values
        $model = new BbiiPost();
        if (isset(\Yii::$app->request->get()['BbiiPost']))
            $model->load(\Yii::$app->request->get()['BbiiPost']);
        // limit posts to approved posts
        $model->approved = 1;
        
        return $this->render('admin',array(
            'model' => $model,
        ));
    }
    
    public function actionIpadmin() {
        //$model = new BbiiIpaddress('search');
        // $model->unsetAttributes();  // clear any default values
        
        $model = new BbiiIpaddress();
        if (isset(\Yii::$app->request->get()['BbiiIpaddress']))
            $model->load(\Yii::$app->request->get()['BbiiIpaddress']);

        return $this->render('ipadmin',array(
            'model' => $model,
        ));
    }
    
    public function actionIpDelete($id) {
        BbiiIpaddress::find($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset(\Yii::$app->request->get()['ajax']))
            $this->redirect(isset(\Yii::$app->request->post()['returnUrl']) ? \Yii::$app->request->post()['returnUrl'] : array('ipadmin'));
    }

    /**
     * Delete a post
     */
    public function actionDelete($id = null) {
        if (isset(\Yii::$app->request->get()['id'])) {
            $id = \Yii::$app->request->get()['id'];
        }

        $post = BbiiPost::find()->where(['id' => (int)$id])->one();

        if ($post === null) {
            throw new \yii\web\HttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
        }

        $forum = BbiiForum::find()->where(['id' => $post->forum_id])->one();
        $topic = BbiiTopic::find()->where(['id' => $post->topic_id])->one();

        // if the posters count is > 0 , reduce it by one
        ($post->poster->posts > 0) ? $post->poster->updateCounters(array('posts' => -1)) : null;

        $post->delete();

        if ($topic->approved == 0) {

            $topic->delete();
        } else {
            $forum->updateCounters(array('num_posts' => -1));         // method since Yii 1.1.8

            if ($topic->num_replies > 0) {

                $topic->updateCounters(array('num_replies' => -1));    // method since Yii 1.1.8
            } else {
                $topic->delete();
                $forum->updateCounters(array('num_topics' => -1));    // method since Yii 1.1.8
            }
        }

        $this->resetFirstTopicPost($id);
        $this->resetLastPost($id);

        // remove messages related to the post
        $messageMDL = BbiiMessage::find()->where(['post_id' => $id])->all();
        if ($messageMDL->id) {
            $messageMDL->delete();
        }
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset(\Yii::$app->request->get()['ajax'])) {

            \Yii::$app->response->redirect(
                isset(\Yii::$app->request->post()['returnUrl'])
                    ? : \Yii::$app->urlManager->createAbsoluteUrl(['forum/forum', 'id' => $forum->id])
            );
        }

        return false;
    }
    
    /**
     * Reset the first post id of a topic when a first post is deleted
     */
    private function resetFirstTopicPost($id = null) {
        $model = BbiiTopic::find()
            ->where(['first_post_id' => $id])
            ->one();

        if (!empty($model)) {

            $post = BbiiPost::find()
                ->where([':topic_id' => $model->id])
                ->orderby('id DESC')
                ->all();

            if ($post !== null) {
                $model->user_id = $post->user_id;
                $model->first_post_id = $post->id;
                return $model->save();
            }
        }

        return false;
    }
    
    /**
     * Reset the last post of a topic and a forum when post is deleted
     */
    private function resetLastPost($id = null) {

        $forum = BbiiForum::find()->where(['last_post_id' => $id])->one();
        if ($forum !== null) {
            $post = BbiiPost::find()
                ->where(['forum_id' => $forum->id, 'approved' => 1])
                ->orderBy('id DESC')
                ->limit(1)
                ->all();

            $forum->last_post_id = ($post === null) ? null : $post->id ;

            $forum->update();
        }

        $topic = BbiiTopic::find()->where(['last_post_id' => $id])->one();
        if ($topic !== null) {
            $post = BbiiPost::find()->where(['topic_id' => $topic->id, 'approved' => 1])
                ->limit(1)
                ->orderBy('id DESC')
                ->one();
            
            $topic->last_post_id = ($post === null) ? null : $post->id ;
            
            $topic->update();
        }
    }
    
    /**
     * Reset the last post of a forum
     */
    private function resetLastForumPost($id) {
        $post = BbiiPost::find()
            ->where(['forum_id' => $id, 'approved' => 1])
            ->orderBy('id DESC');

        $model = BbiiForum::find()
            ->where(['id' => $id])
            ->one();

        $model->last_post_id = ($post !== null) ? $post->id : null;

        return $model->save();
    }
    
    public function actionReport() {
        //$model = new BbiiMessage('search');
        // $model->unsetAttributes();  // clear any default values
        
        $model = new BbiiMessage();
        if (isset(\Yii::$app->request->get()['BbiiMessage']))
            $model->load(\Yii::$app->request->get()['BbiiMessage']);
        // limit posts to moderator inbox
        $model->sendto = 0;
        
        return $this->render('report',array(
            'model' => $model,
        ));
    }
    
    public function actionView() {
        $json = array();
        if (isset(\Yii::$app->request->post()['id'])) {
            $model = BbiiPost::find(\Yii::$app->request->post()['id']);
            if ($model !== null) {
                $poll = BBiiPoll::find()->findByAttributes(array('post_id' => $model->id));
                $choices = array();
                if ($poll !== null) {
                    $chs = BbiiChoice::find()->findAllByAttributes(array('poll_id' => $poll->id));
                    foreach($chs as $choice) {
                        $choices[] = $choice->choice;
                    }
                }
                $json['success'] = 'yes';
                $json['html'] = $this->render('_view', array('model' => $model, 'poll' => $poll, 'choices' => $choices), true);
            } else {
                $json['success'] = 'no';
                $json['message'] = Yii::t('BbiiModule.bbii', 'Post not found.');
            }
        } else {
            $json['success'] = 'no';
            $json['message'] = Yii::t('BbiiModule.bbii', 'Post not found.');
        }
        echo json_encode($json);
        \Yii::$app->end();
    }
    
    public function actionTopic() {
        $json = array();
        if (isset(\Yii::$app->request->post()['id'])) {
            $model = BbiiTopic::find(\Yii::$app->request->post()['id']);
            if ($model === null) {
                $json['success'] = 'no';
                $json['message'] = Yii::t('BbiiModule.bbii', 'Topic not found.');
            } else {
                $json['success'] = 'yes';
                $json['forum_id'] = $model->forum_id;
                $json['title'] = $model->title;
                $json['locked'] = $model->locked;
                $json['sticky'] = $model->sticky;
                $json['global'] = $model->global;
                $json['option'] = '<option value = ""></option>';
                foreach(BbiiTopic::find()->findAll("forum_id = $model->forum_id") as $topic) {
                    $json['option'] .= '<option value = "' . $topic->id. '">' . $topic->title . '</option>';
                }
            }
        } else {
            $json['success'] = 'no';
            $json['message'] = Yii::t('BbiiModule.bbii', 'Topic not found.');
        }
    
        echo json_encode($json);
        \Yii::$app->end();
    }
    
    /**
     * Ajax call for retrieving option list of topics of a forum
     */
    public function actionRefreshTopics() {
        $json = array();
        if (isset(\Yii::$app->request->post()['id'])) {
            $json['success'] = 'yes';
            $json['option'] = '<option value = ""></option>';
            foreach(BbiiTopic::find()->findAll('forum_id = ' . \Yii::$app->request->post()['id']) as $topic) {
                $json['option'] .= '<option value = "' . $topic->id. '">' . $topic->title . '</option>';
            }
        } else {
            $json['success'] = 'no';
            $json['message'] = Yii::t('BbiiModule.bbii', 'Topic not found.');
        }
    
        echo json_encode($json);
        \Yii::$app->end();
    }
    
    /**
     * Ajax call for change, move or merge topic
     */
    public function actionChangeTopic() {
        $json = array();
        if (isset(\Yii::$app->request->post()['BbiiTopic'])) {
            $model = BbiiTopic::find(\Yii::$app->request->post()['BbiiTopic']['id']);
            $move = false;
            $merge = false;
            $sourceTopicId = \Yii::$app->request->post()['BbiiTopic']['id'];
            $sourceForumId = $model->forum_id;
            if ($model->forum_id != \Yii::$app->request->post()['BbiiTopic']['forum_id']) {
                $move = true;
                $targetForumId = \Yii::$app->request->post()['BbiiTopic']['forum_id'];
            }
            if (!empty(\Yii::$app->request->post()['BbiiTopic']['merge']) && \Yii::$app->request->post()['BbiiTopic']['id'] != \Yii::$app->request->post()['BbiiTopic']['merge']) {
                $merge = true;
                $targetTopicId = \Yii::$app->request->post()['BbiiTopic']['merge'];
            }
            $model->load(\Yii::$app->request->post()['BbiiTopic']);
            if ($model->validate()) {
                $json['success'] = 'yes';
                if ($merge || $move) {
                    $numberOfPosts = BbiiPost::find()->where(['topic_id' => $sourceTopicId])->approved()->count();
                    if ($move) {
                        BbiiPost::find()->updateAll(array('forum_id' => $targetForumId), $criteria);
                        $forum = BbiiForum::find($sourceForumId);
                        $forum->updateCounters(array('num_topics' => -1));
                        $forum->updateCounters(array('num_posts' => -$numberOfPosts));
                        $forum = BbiiForum::find($targetForumId);
                        $forum->updateCounters(array('num_topics' => 1));
                        $forum->updateCounters(array('num_posts' => $numberOfPosts));
                        $this->resetLastForumPost($sourceForumId);
                        $this->resetLastForumPost($targetForumId);
                    }
                    if ($merge) {
                        BbiiPost::find()->updateAll(array('topic_id' => $targetTopicId), $criteria);
                        if ($move) {
                            $forum = BbiiForum::find($targetForumId);
                        } else {
                            $forum = BbiiForum::find($sourceForumId);
                        }
                        $forum->updateCounters(array('num_topics' => -1));
                        $topic = BbiiTopic::find($targetTopicId);
                        $topic->updateCounters(array('num_replies' => $numberOfPosts));
                        $model->delete();
                    } else {
                        $model->save();
                    }
                } else {    // no move or merge involved
                    $model->save();
                }
            } else {
                $json['error'] = json_decode(ActiveForm::validate($model));
            }
        }
        echo json_encode($json);
        \Yii::$app->end();
    }
    
    public function actionBanIp($id) {
        $post = BbiiPost::find($id);
        if ($post === null) {
            throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
        }
        $ip = new BbiiIpaddress;
        $ip->ip = $post->ip;
        $ip->save();
        return;
    }
    
    private function assignMembergroup($id) {
        $member = BbiiMember::find($id);
        $group = BbiiMembergroup::find($member->group_id);
        if ($group !== null && $group->min_posts < 0) {
            return;
        }

        $newGroup = BbiiMembergroup::find()
            ->where("min_posts > 0 and min_posts < =  " . $member->posts)
            ->orderBy('min_posts DESC');

        if ($newGroup !== null and $group->id != $newGroup->id) {
            $member->group_id = $newGroup->id;
            $member->save();
        }
    }
    
    public function actionSendmail() {
        $model = new MailForm;
        // $model->unsetAttributes();

        if (isset(\Yii::$app->request->post()['MailForm'])) {
            $model->load(\Yii::$app->request->post()['MailForm']);
            if (empty($model->member_id)) {
                $model->member_id = -1;    // All members
            }

            if ($model->validate()) {
                
                $members = ($model->member_id >=  0)
                    ? BbiiMember::find()->where(['group_id' => $model->member_id])->all()
                    : BbiiMember::findAll();

                if (isset(\Yii::$app->request->post()['email'])) {    // e-mails
                    $name = $this->context->module->forumTitle;
                    $name = ' = ?UTF-8?B?'.base64_encode($name).'? = ';
                    $from = BbiiSetting::find()->find()->contact_email;
                    $subject = ' = ?UTF-8?B?'.base64_encode($model->subject).'? = ';
                    $headers = "From: $name <$from>\r\n".
                        "Reply-To: $from\r\n".
                        "MIME-Version: 1.0\r\n".
                        "Content-type: text/html; charset = UTF-8";

                    $users = array();
                    $class = new $this->module->userClass;

                    foreach($members as $member) {
                        $sendto = $member->member_name . " <$to>";
                        $to     = $user->getAttribute($this->module->userMailColumn);
                        $user   = $class::find()->where([$this->module->userIdColumn => $member->id])->all();
                        
                        mail($sendto,$subject,$model->body,$headers);
                        $users[] = $member->member_name;
                    }

                    // $model->unsetAttributes();
                    \Yii::$app->session->addFlash('success',Yii::t('BbiiModule.bbii','You have sent an e-mail to the following users: ') . implode(', ', $users));
                } else {                        // private messages
                    $users = array();
                    foreach($members as $member) {
                        $message = new BbiiMessage;
                        $message->sendfrom = \Yii::$app->user->identity->id ;
                        $message->sendto = $member->id;
                        $message->subject = $model->subject;
                        $message->content = $model->body;
                        $message->outbox = 0;
                        if ($message->save()) {
                            $users[] = $member->member_name;
                        }
                    }
                    // $model->unsetAttributes();
                    \Yii::$app->session->addFlash('success',Yii::t('BbiiModule.bbii','You have sent a private message to the following users: ') . implode(', ', $users));
                }
            }
        }
        return $this->render('sendmail', array(
            'model' => $model, 
        ));
    }
}