<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\components\BbiiController;


use Yii;
use yii\web\Session;
use yii\data\ActiveDataProvider;

class MessageController extends BbiiController {
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('create', 'delete', 'inbox', 'outbox', 'reply', 'view', 'update', 'sendReport'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * [actionInbox description]
     *
     * @todo  combine w/ actionOutbox()
     * @version  2.4.0
     * @param  integer $id
     * @return array
     */
    public function actionInbox($id = null) {
        /*
        if (!(isset($id) && $this->isModerator())) {
            $id = \Yii::$app->user->identity->id ;
        }
        $count['inbox'] = BbiiMessage::find()->inbox()->count('inbox = 1 and sendto = '.$id);
        $count['outbox'] = BbiiMessage::find()->outbox()->count('outbox = 1 and sendfrom = '.$id);
        $model = new BbiiMessage('search');
        // $model->unsetAttributes();  // clear any default values
        if (isset(\Yii::$app->request->get()['BbiiMessage'])) {
            $model->load(\Yii::$app->request->get()['BbiiMessage']);
        }
        // restrict filtering to own inbox
        $model->sendto = $id;
        $model->inbox  = 1;
        */
        
        $model = new ActiveDataProvider([
            'query' => BbiiMessage::find()
                ->where(['sendto' => \Yii::$app->user->identity->id, 'inbox' => 1])
                ->orderBy('create_time DESC'),
            'sort' => false
        ]);

        return $this->render('inbox', array(
            'inboxCount' => $model->getTotalCount(),
            'model' => $model, 
        ));
    }

    /**
     * [actionInbox description]
     *
     * @todo  combine w/ actionInbox()
     * @version  2.4.0
     * @param  integer $id
     * @return array
     */
    public function actionOutbox($id = null) {
        /* if (!(isset($id) && $this->isModerator())) {
            $id = \Yii::$app->user->identity->id ;
        }

        $model = new BbiiMessage;
        // $model->unsetAttributes();  // clear any default values
        if (isset(\Yii::$app->request->get()['BbiiMessage'])) {
            $model->load(\Yii::$app->request->get()['BbiiMessage']);
        }
        // restrict filtering to own outbox
        $model->sendfrom = $id;
        $model->outbox = 1;
        
        return $this->render('outbox', array(
            'model' => $model,
            'count' => $this->getMessageCount(),
        )); */

        $model = new ActiveDataProvider([
            'query' => BbiiMessage::find()
                ->where(['sendto' => \Yii::$app->user->identity->id, 'outbox' => 1])
                ->orderBy('create_time DESC'),
            'sort' => false
        ]);

        return $this->render('outbox', array(
            'outboxCount' => $model->getTotalCount(),
            'model' => $model, 
        ));
    }

    /**
     * [actionCreate description]
     *
     * @depricated 2.5.0
     * @param  [type] $id   [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    /* public function actionCreate($id = null, $type = null) {
        $model = new BbiiMessage;
        $count['inbox']  = BbiiMessage::find()->inbox()->count('sendto = '.\Yii::$app->user->identity->id );
        $count['outbox'] = BbiiMessage::find()->outbox()->count('sendfrom = '.\Yii::$app->user->identity->id );

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (\Yii::$app->request->post('BbiiMessage')) {
            $model->load(\Yii::$app->request->post('BbiiMessage'));
            //$model->search = \Yii::$app->request->post()['BbiiMessage']['search'];
            //$model->sendfrom = \Yii::$app->user->identity->id ;

            if ($model->validate() && empty(\Yii::$app->request->post('BbiiMessage')['search'])) {
                unset($model->sendto);
            } else {
                // $criteria = new CDbCriteria;
                // $criteria->condition = 'member_name = :search';
                // $criteria->params = array(':search' => \Yii::$app->request->post()['BbiiMessage']['search']);
                // $member = BbiiMember::find()->find($criteria);
                $member = BbiiMember::find()->where(['member_name' => \Yii::$app->request->post('BbiiMessage')['search']])->one();
                if ($member === null) {
                    unset($model->sendto);
                    $model->addError('sendto', Yii::t('BbiiModule.bbii','Member not found'));
                } else {
                    $model->sendto = $member->id;
                    if ($this->isModerator()) {
                        $allowed = true;
                    } else {
                        $allowed = BbiiMember::find($model->sendto)->contact_pm;
                    }
                    if (!$allowed) {
                        $model->addError('sendto', Yii::t('BbiiModule.bbii','This user does not want to receive private messages.'));
                    }
                    if ($allowed && $model->save()) {
                        return \Yii::$app->response->redirect(array('forum/outbox'));
                    }
                }
            }
        } elseif (isset($id)) {
            $model->sendto = $id;
            $model->search = $model->receiver->member_name;
            if ($this->isModerator() && isset($type)) {
                $model->type = $type;
            }
        }

        return $this->render('create',array(
            'model' => $model,
            'count' => $count,
        ));
    }*/ 

    /**
     * [actionCreate description]
     *
     * @version  2.5.0
     * @param  [type] $id   [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function actionCreate($type = 1) {
        $model = new BbiiMessage;

        if (\Yii::$app->request->post('BbiiMessage')) {
            // automatic attrib set
            $model->setAttributes(\Yii::$app->request->post('BbiiMessage'));
            
            // manual sttrib set
            $model->sendfrom = \Yii::$app->user->identity->id ;
            $model->sendto = BbiiMember::find()
                ->where(['member_name' => \Yii::$app->request->post('BbiiMessage')['sendto']])
                ->one()
                ->id;

            $model->type = $type;

            if ($model->validate() && $model->save()) {

                \Yii::$app->session->addFlash('success', Yii::t('BbiiModule.bbii', 'Message sent successfully.'));
            } else {

                \Yii::$app->session->addFlash('warning',Yii::t('BbiiModule.bbii', 'Could not send message.'));
            }

            return \Yii::$app->response->redirect(array('forum/message/inbox'));
        } elseif (\Yii::$app->request->get('sendto') !== null) {

            $model->sendto = BbiiMember::find()
                ->where(['id' => \Yii::$app->request->get('sendto')])
                ->one()
                ->member_name;

            //$model->search = $model->receiver->member_name;
            if ($this->isModerator() && isset($type)) {
                $model->type = $type;
            }
        }

        return $this->render('create',array(
            'model' => $model,
            'count' => $this->getMessageCount(),
        ));
    }
    
    /**
     * [actionReply description]
     *
     * @todo  Iterate on the 'reply' functionality - DJE : 2015-05-20
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionReply($id = null) {
        if (isset(\Yii::$app->request->post()['BbiiMessage'])) {
            $model = new BbiiMessage;
            $model->load(\Yii::$app->request->post()['BbiiMessage']);
            $model->sendfrom = \Yii::$app->user->identity->id ;
            if ($model->save())
                return \Yii::$app->response->redirect(array('forum/outbox'));
        } else {
            $model = BbiiMessage::find($id);
            if ($model->sendto != \Yii::$app->user->identity->id  && !$this->isModerator()) {
                throw new HttpException(404, Yii::t('BbiiModule.bbii', 'The requested message does not exist.'));
            }
            $model->sendto = $model->sendfrom;
            $model->search = $model->receiver->member_name;
            $quote = $model->receiver->member_name .' '. Yii::t('BbiiModule.bbii', 'wrote') .' '. Yii::t('BbiiModule.bbii', 'on') .' '. \Yii::$app->formatter->asDatetime($model->create_time);
            $model->content = '<blockquote cite = "'. $quote .'"><p class = "blockquote-header"><strong>'. $quote .'</strong></p>' . $model->content . '</blockquote><p></p>';
        }

        return $this->render('create', array(
            'model' => $model,
            'count' => $this->getMessageCount(),
        ));
    }
    
    /**
     * Remove a message by removing assigned in/outbox
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionDelete($id = null) {
        $model = $this->getMessageMDL($id);

        //softDelete
        // toto usea real softDelete behavior like provided by davidjeddy/yii2-utility-classes - DJE : 2015-07-29
        if ($model->sendto == \Yii::$app->user->identity->id  || $model->sendto == 0) {
            $model->inbox = 0;
        }

        if ($model->sendfrom == \Yii::$app->user->identity->id ) {
            $model->outbox = 0;
        }

        // update MDL
        if ($model->validate() && $model->update()) {
        
            \Yii::$app->session->addFlash('success', Yii::t('BbiiModule.bbii', 'Message removed.'));
        } else {

            \Yii::$app->session->addFlash('warning', Yii::t('BbiiModule.bbii', 'Message NOT removed.'));
        }

        return \Yii::$app->response->redirect(Yii::$app->request->referrer);
    }
    
    /**
     * handle Ajax call for viewing message
     *
     * @deprecated 2.5.0 VIEW is no longer json only response
     */
    /*public function actionView() {
        $json = array();
        if (isset(\Yii::$app->request->post()['id'])) {
            $model = BbiiMessage::find(\Yii::$app->request->post()['id']);
            if ($model !== null && ($this->isModerator() || $model->sendto == \Yii::$app->user->identity->id  || $model->sendfrom == \Yii::$app->user->identity->id )) {
                $json['success'] = 'yes';
                $json['html'] = $this->render('_view', array('model' => $model), true);
                if ($model->sendto == \Yii::$app->user->identity->id ) {
                    $model->read_indicator = 1;
                    $model->update();
                }
            } else {
                $json['success'] = 'no';
                $json['message'] = Yii::t('BbiiModule.bbii', 'Message not found.');
            }
        } else {
            $json['success'] = 'no';
            $json['message'] = Yii::t('BbiiModule.bbii', 'Message not found.');
        }
        echo json_encode($json);
        \Yii::$app->end();
    }*/
    
    /**
     * Display message contents
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionView($id = null) {
        $model = $this->getMessageMDL($id);

        // @todo This should be done at the VW level - DJE : 2015-08-03
        $model->sendfrom = BbiiMember::find()->where(['id' => $model->sendfrom])->one()->member_name;

        // mark message as viewed
        $model->read_indicator = true;
        $model->save();

        // send message data to VW
        return $this->render('view', array(
            'count' => $this->getMessageCount(),
            'model' => $model,
        ));
    }

    /**
     * handle Ajax call for sending a report on a post
     */
    public function actionSendReport() {
        $json = array();
        if (isset(\Yii::$app->request->post()['BbiiMessage'])) {
            $model = new BbiiMessage;
            $model->load(\Yii::$app->request->post()['BbiiMessage']);
            $model->subject = Yii::t('BbiiModule.bbii', 'Post reported: ') . BbiiPost::find($model->post_id)->subject;
            $model->sendto = 0;
            $model->sendfrom = \Yii::$app->user->identity->id ;
            $model->outbox = 0;
            $model->type = 2;
            if ($model->save()) {
                $json['success'] = 'yes';
                $json['message'] = Yii::t('BbiiModule.bbii', 'Thank you for your report.');
            } else {
                $json['success'] = 'no';
                $json['message'] = Yii::t('BbiiModule.bbii', 'Could not register your report.');
            }
        }
        echo json_encode($json);
        \Yii::$app->end();
    }
    
    /**
     * Performs the AJAX validation.
     * @param BbiiMessage $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset(\Yii::$app->request->post()['ajax']) && \Yii::$app->request->post()['ajax'] === 'message-form')
        {
            echo ActiveForm::validate($model);
            \Yii::$app->end();
        }
    }



    // Private methods to reduce overall repeated logic



    /**
     * Get the count of inbox and outbox messages
     *
     * @author  David Eddy <me@davidjeddy.com>
     * @version 2.5.0
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    private function getMessageCount($param = null) {
        return [
            'inbox'  => BbiiMessage::find()->inbox()->count('inbox = 1 and sendto = '.\Yii::$app->user->identity->id ),
            'outbox' => BbiiMessage::find()->outbox()->count('outbox = 1 and sendfrom = '.\Yii::$app->user->identity->id )
        ];
    }

    /**
     * [getMessageID description]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    private function getMessageID($param = null) {

        if (is_numeric($param)) { return $param; }

        $param = \Yii::$app->request->post('BbiiMessage')['id'];
        if (is_numeric($param)) {
            return $param;
        }

        $param = \Yii::$app->request->get('id');
        if (is_numeric($param)) {
            return $param;
        }

        return $param;
    }

    /**
     * [getMessageMDL description]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    private function getMessageMDL($param) {
        return BbiiMessage::find()->where(['id' => $this->getMessageID($param)])->one();
    }
}