<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiIpaddress;
use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\MailForm;

use yii;

class ModeratorController extends BbiiController {
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
				'actions' => array('admin','approval','approve','banIp','changeTopic','delete','ipAdmin','ipDelete','view','refreshTopics','report','topic','sendmail'),
				'users' => array('@'),
				'expression' => ($this->isModerator())?'true':'false',
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionApproval() {
		$model = new BbiiPost('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMessage'])) {
			$model->attributes = $_GET['BbiiPost'];
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
			throw new CHttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
		}
		$forum = BbiiForum::find($post->forum_id);
		$topic = BbiiTopic::find($post->topic_id);
		if ($topic->approved == 0) {
			$topic->approved = 1;
			$topic->update();
			$forum->saveCounters(array('num_topics' => 1));	// method since Yii 1.1.8
		} else {
			$topic->saveCounters(array('num_replies' => 1));				// method since Yii 1.1.8
		}
		$topic->saveAttributes(array('last_post_id' => $post->id));
		$post->approved = 1;
		$post->update();
		$this->resetLastForumPost($forum->id);
		$forum->saveCounters(array('num_posts' => 1));		// method since Yii 1.1.8
		$post->poster->saveCounters(array('posts' => 1));		// method since Yii 1.1.8
		$this->assignMembergroup($post->user_id);
		
		return Yii::$app->response->redirect(array('forum/approval'));
	}
	
	public function actionAdmin() {
		$model = new BbiiPost('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiPost']))
			$model->attributes = $_GET['BbiiPost'];
		// limit posts to approved posts
		$model->approved = 1;
		
		return $this->render('admin',array(
			'model' => $model,
		));
	}
	
	public function actionIpadmin()
	{
		$model = new BbiiIpaddress('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiIpaddress']))
			$model->attributes = $_GET['BbiiIpaddress'];

		return $this->render('ipadmin',array(
			'model' => $model,
		));
	}
	
	public function actionIpDelete($id) {
		BbiiIpaddress::find($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset(Yii::$app->request->post()['returnUrl']) ? Yii::$app->request->post()['returnUrl'] : array('ipadmin'));
	}

	/**
	 * Delete a post
	 */
	public function actionDelete($id) {
		if (isset($_GET['id']))
			$id = $_GET['id'];
		$post = BbiiPost::find($id);
		if ($post === null) {
			throw new CHttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
		}
		$forum = BbiiForum::find($post->forum_id);
		$topic = BbiiTopic::find($post->topic_id);
		$post->poster->saveCounters(array('posts' => -1));
		$post->delete();
		if ($topic->approved == 0) {
			$topic->delete();
		} else {
			$forum->saveCounters(array('num_posts' => -1));					// method since Yii 1.1.8
			if ($topic->num_replies > 0) {
				$topic->saveCounters(array('num_replies' => -1));				// method since Yii 1.1.8
			} else {
				$topic->delete();
				$forum->saveCounters(array('num_topics' => -1));				// method since Yii 1.1.8
			}
		}
		$this->resetFirstTopicPost($id);
		$this->resetLastPost($id);
		// Delete reports on the delete post
		$criteria = new CDbCriteria();
		$criteria->condition = 'post_id = :post_id';
		$criteria->params = array(':post_id' => $id);
		$model = BbiiMessage::find()->deleteAll($criteria);
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset(Yii::$app->request->post()['returnUrl']) ? Yii::$app->request->post()['returnUrl'] : array('approval'));
		return;
	}
	
	/**
	 * Reset the first post id of a topic when a first post is deleted
	 */
	private function resetFirstTopicPost($id) {
		$criteria = new CDbCriteria();
		$criteria->condition = 'first_post_id = :first_post_id';
		$criteria->params = array(':first_post_id' => $id);
		$model = BbiiTopic::find()->find($criteria);
		if ($model !== null) {
			$criteria->condition = 'topic_id = :topic_id';
			$criteria->params = array(':topic_id' => $model->id);
			$criteria->order = 'id DESC';
			$post = BbiiPost::find()->find($criteria);
			if ($post !== null) {
				$model->user_id = $post->user_id;
				$model->first_post_id = $post->id;
				$model->save();
			}
		}
	}
	
	/**
	 * Reset the last post of a topic and a forum when post is deleted
	 */
	private function resetLastPost($id) {
		$criteria = new CDbCriteria;
		$criteria->condition = "last_post_id = $id";
		$forum = BbiiForum::find()->find($criteria);
		$topic = BbiiTopic::find()->find($criteria);
		if ($forum !== null) {
			$criteria->condition = "forum_id = {$forum->id} and approved = 1";
			$criteria->order = 'id DESC';
			$criteria->limit = 1;
			$post = BbiiPost::find()->find($criteria);
			if ($post === null) {
				$forum->last_post_id = null;
			} else {
				$forum->last_post_id = $post->id;
			}
			$forum->update();
		}
		if ($topic !== null) {
			$criteria->condition = "topic_id = $topic->id and approved = 1";
			$criteria->order = 'id DESC';
			$criteria->limit = 1;
			$post = BbiiPost::find()->find($criteria);
			if ($post === null) {
				$topic->last_post_id = null;
			} else {
				$topic->last_post_id = $post->id;
			}
			$topic->update();
		}
	}
	
	/**
	 * Reset the last post of a forum
	 */
	private function resetLastForumPost($id) {
		$model = BbiiForum::find($id);
		$criteria = new CDbCriteria;
		$criteria->condition = "forum_id = $id and approved = 1";
		$criteria->order = 'id DESC';
		$post = BbiiPost::find()->find($criteria);
		if ($post !== null) {
			$model->last_post_id = $post->id;
		} else {
			$model->last_post_id = null;
		}
		$model->save();
	}
	
	public function actionReport() {
		$model = new BbiiMessage('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMessage']))
			$model->attributes = $_GET['BbiiMessage'];
		// limit posts to moderator inbox
		$model->sendto = 0;
		
		return $this->render('report',array(
			'model' => $model,
		));
	}
	
	public function actionView() {
		$json = array();
		if (isset(Yii::$app->request->post()['id'])) {
			$model = BbiiPost::find(Yii::$app->request->post()['id']);
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
		Yii::$app->end();
	}
	
	public function actionTopic() {
		$json = array();
		if (isset(Yii::$app->request->post()['id'])) {
			$model = BbiiTopic::find(Yii::$app->request->post()['id']);
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
		Yii::$app->end();
	}
	
	/**
	 * Ajax call for retrieving option list of topics of a forum
	 */
	public function actionRefreshTopics() {
		$json = array();
		if (isset(Yii::$app->request->post()['id'])) {
			$json['success'] = 'yes';
			$json['option'] = '<option value = ""></option>';
			foreach(BbiiTopic::find()->findAll('forum_id = ' . Yii::$app->request->post()['id']) as $topic) {
				$json['option'] .= '<option value = "' . $topic->id. '">' . $topic->title . '</option>';
			}
		} else {
			$json['success'] = 'no';
			$json['message'] = Yii::t('BbiiModule.bbii', 'Topic not found.');
		}
	
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * Ajax call for change, move or merge topic
	 */
	public function actionChangeTopic() {
		$json = array();
		if (isset(Yii::$app->request->post()['BbiiTopic'])) {
			$model = BbiiTopic::find(Yii::$app->request->post()['BbiiTopic']['id']);
			$move = false;
			$merge = false;
			$sourceTopicId = Yii::$app->request->post()['BbiiTopic']['id'];
			$sourceForumId = $model->forum_id;
			if ($model->forum_id != Yii::$app->request->post()['BbiiTopic']['forum_id']) {
				$move = true;
				$targetForumId = Yii::$app->request->post()['BbiiTopic']['forum_id'];
			}
			if (!empty(Yii::$app->request->post()['BbiiTopic']['merge']) && Yii::$app->request->post()['BbiiTopic']['id'] != Yii::$app->request->post()['BbiiTopic']['merge']) {
				$merge = true;
				$targetTopicId = Yii::$app->request->post()['BbiiTopic']['merge'];
			}
			$model->attributes = Yii::$app->request->post()['BbiiTopic'];
			if ($model->validate()) {
				$json['success'] = 'yes';
				if ($merge || $move) {
					$criteria = new CDbCriteria();
					$criteria->condition = "topic_id = $sourceTopicId";
					$numberOfPosts = BbiiPost::find()->approved()->count($criteria);
					if ($move) {
						BbiiPost::find()->updateAll(array('forum_id' => $targetForumId), $criteria);
						$forum = BbiiForum::find($sourceForumId);
						$forum->saveCounters(array('num_topics' => -1));
						$forum->saveCounters(array('num_posts' => -$numberOfPosts));
						$forum = BbiiForum::find($targetForumId);
						$forum->saveCounters(array('num_topics' => 1));
						$forum->saveCounters(array('num_posts' => $numberOfPosts));
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
						$forum->saveCounters(array('num_topics' => -1));
						$topic = BbiiTopic::find($targetTopicId);
						$topic->saveCounters(array('num_replies' => $numberOfPosts));
						$model->delete();
					} else {
						$model->save();
					}
				} else {	// no move or merge involved
					$model->save();
				}
			} else {
				$json['error'] = json_decode(CActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	public function actionBanIp($id) {
		$post = BbiiPost::find($id);
		if ($post === null) {
			throw new CHttpException(404, Yii::t('BbiiModule.bbii', 'The requested post does not exist.'));
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
		$criteria = new CDbCriteria;
		$criteria->condition = "min_posts > 0 and min_posts < =  " . $member->posts;
		$criteria->order = 'min_posts DESC';
		$newGroup = BbiiMembergroup::find()->find($criteria);
		if ($newGroup !== null and $group->id != $newGroup->id) {
			$member->group_id = $newGroup->id;
			$member->save();
		}
	}
	
	public function actionSendmail() {
		$model = new MailForm;
		// $model->unsetAttributes();
		if (isset(Yii::$app->request->post()['MailForm'])) {
			$model->attributes = Yii::$app->request->post()['MailForm'];
			if (empty($model->member_id)) {
				$model->member_id = -1;	// All members
			}
			if ($model->validate()) {
				$criteria = new CDbCriteria;
				if ($model->member_id >=  0) {
					$criteria->condition = 'group_id = :group';
					$criteria->params = array(':group' => $model->member_id);
				}
				$members = BbiiMember::find()->findAll($criteria);
				if (isset(Yii::$app->request->post()['email'])) {	// e-mails
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
					$criteria = new CDbCriteria;
					$criteria->condition = $this->module->userIdColumn . ' = :id';
					foreach($members as $member) {
						$criteria->params = array(':id' => $member->id);
						$user 	 =  $class::find()->find($criteria);
						$to 	 =  $user->getAttribute($this->module->userMailColumn);
						$sendto = $member->member_name . " <$to>";
						mail($sendto,$subject,$model->body,$headers);
						$users[] = $member->member_name;
					}
					// $model->unsetAttributes();
					Yii::$app->user->setFlash('success',Yii::t('BbiiModule.bbii','You have sent an e-mail to the following users: ') . implode(', ', $users));
				} else {						// private messages
					$users = array();
					foreach($members as $member) {
						$message = new BbiiMessage;
						$message->sendfrom = Yii::$app->user->id;
						$message->sendto = $member->id;
						$message->subject = $model->subject;
						$message->content = $model->body;
						$message->outbox = 0;
						if ($message->save()) {
							$users[] = $member->member_name;
						}
					}
					// $model->unsetAttributes();
					Yii::$app->user->setFlash('success',Yii::t('BbiiModule.bbii','You have sent a private message to the following users: ') . implode(', ', $users));
				}
			}
		}
		return $this->render('sendmail', array(
			'model' => $model, 
		));
	}
}