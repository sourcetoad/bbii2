<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\components\BbiiController;

use yii;

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
	 * @version  2.4.0
	 * @param  integer $id
	 * @return array
	 */
	public function actionInbox($id = null) {
		/*
		if (!(isset($id) && $this->isModerator())) {
			$id = Yii::$app->user->id;
		}
		$count['inbox'] = BbiiMessage::find()->inbox()->count('inbox = 1 and sendto = '.$id);
		$count['outbox'] = BbiiMessage::find()->outbox()->count('outbox = 1 and sendfrom = '.$id);
		$model = new BbiiMessage('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMessage'])) {
			$model->attributes = $_GET['BbiiMessage'];
		}
		// restrict filtering to own inbox
		$model->sendto = $id;
		$model->inbox = 1;
		*/

		$id = ($id != null) ? $id : Yii::$app->user->id;
		
		$count['inbox']  = BbiiMessage::find()->inbox()->count('inbox = 1 and sendto = '.$id);
		$count['outbox'] = BbiiMessage::find()->outbox()->count('outbox = 1 and sendfrom = '.$id);

		$model           = new BbiiMessage();
		$model->setAttributes( (isset($_GET['BbiiMessage']) ? $_GET['BbiiMessage'] : ['inbox' => 1, 'sendto' => $id]) );
		$model           = $model->search();

		return $this->render('inbox', array(
			'model' => $model, 
			'count' => $count
		));
	}

	public function actionOutbox($id = null) {
		if (!(isset($id) && $this->isModerator())) {
			$id = Yii::$app->user->id;
		}
		$count['inbox'] = BbiiMessage::find()->inbox()->count('inbox = 1 and sendto = '.$id);
		$count['outbox'] = BbiiMessage::find()->outbox()->count('outbox = 1 and sendfrom = '.$id);
		$model = new BbiiMessage('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMessage'])) {
			$model->attributes = $_GET['BbiiMessage'];
		}
		// restrict filtering to own outbox
		$model->sendfrom = $id;
		$model->outbox = 1;
		
		return $this->render('outbox', array(
			'model' => $model,
			'count' => $count,
		));
	}
	
	public function actionCreate($id = null, $type = null) {
		$model = new BbiiMessage;
		$uid = Yii::$app->user->id;
		$count['inbox'] = BbiiMessage::find()->inbox()->count('sendto = '.$uid);
		$count['outbox'] = BbiiMessage::find()->outbox()->count('sendfrom = '.$uid);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset(Yii::$app->request->post()['BbiiMessage'])) {
			$model->attributes = Yii::$app->request->post()['BbiiMessage'];
			$model->search = Yii::$app->request->post()['BbiiMessage']['search'];
			$model->sendfrom = Yii::$app->user->id;
			if (empty(Yii::$app->request->post()['BbiiMessage']['search'])) {
				unset($model->sendto);
			} else {
				$criteria = new CDbCriteria;
				$criteria->condition = 'member_name = :search';
				$criteria->params = array(':search' => Yii::$app->request->post()['BbiiMessage']['search']);
				$member = BbiiMember::find()->find($criteria);
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
						return Yii::$app->response->redirect(array('forum/outbox'));
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
	}
	
	public function actionReply($id) {
		$count['inbox'] = BbiiMessage::find()->inbox()->count('sendto = '.Yii::$app->user->id);
		$count['outbox'] = BbiiMessage::find()->outbox()->count('sendfrom = '.Yii::$app->user->id);
		if (isset(Yii::$app->request->post()['BbiiMessage'])) {
			$model = new BbiiMessage;
			$model->attributes = Yii::$app->request->post()['BbiiMessage'];
			$model->sendfrom = Yii::$app->user->id;
			if ($model->save())
				return Yii::$app->response->redirect(array('forum/outbox'));
		} else {
			$model = BbiiMessage::find($id);
			if ($model->sendto != Yii::$app->user->id && !$this->isModerator()) {
				throw new CHttpException(404, Yii::t('BbiiModule.bbii', 'The requested message does not exist.'));
			}
			$model->sendto = $model->sendfrom;
			$model->search = $model->receiver->member_name;
			$quote = $model->receiver->member_name .' '. Yii::t('BbiiModule.bbii', 'wrote') .' '. Yii::t('BbiiModule.bbii', 'on') .' '. DateTimeCalculation::long($model->create_time);
			$model->content = '<blockquote cite = "'. $quote .'"><p class = "blockquote-header"><strong>'. $quote .'</strong></p>' . $model->content . '</blockquote><p></p>';
		}

		return $this->render('create', array(
			'model' => $model,
			'count' => $count,
		));
	}
	
	public function actionDelete($id) {
		$model = BbiiMessage::find($id);
		if ($model->sendto == Yii::$app->user->id || $model->sendto == 0) {
			$model->inbox = 0;
		}
		if ($model->sendfrom == Yii::$app->user->id) {
			$model->outbox = 0;
		}
		if ($model->inbox || $model->outbox) {
			$model->update();
		} else {
			$model->delete();
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset(Yii::$app->request->post()['returnUrl']) ? Yii::$app->request->post()['returnUrl'] : array('inbox'));
	}
	
	/**
	 * handle Ajax call for viewing message
	 */
	public function actionView() {
		$json = array();
		if (isset(Yii::$app->request->post()['id'])) {
			$model = BbiiMessage::find(Yii::$app->request->post()['id']);
			if ($model !== null && ($this->isModerator() || $model->sendto == Yii::$app->user->id || $model->sendfrom == Yii::$app->user->id)) {
				$json['success'] = 'yes';
				$json['html'] = $this->render('_view', array('model' => $model), true);
				if ($model->sendto == Yii::$app->user->id) {
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
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for sending a report on a post
	 */
	public function actionSendReport() {
		$json = array();
		if (isset(Yii::$app->request->post()['BbiiMessage'])) {
			$model = new BbiiMessage;
			$model->attributes = Yii::$app->request->post()['BbiiMessage'];
			$model->subject = Yii::t('BbiiModule.bbii', 'Post reported: ') . BbiiPost::find($model->post_id)->subject;
			$model->sendto = 0;
			$model->sendfrom = Yii::$app->user->id;
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
		Yii::$app->end();
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param BbiiMessage $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset(Yii::$app->request->post()['ajax']) && Yii::$app->request->post()['ajax'] === 'message-form')
		{
			echo CActiveForm::validate($model);
			Yii::$app->end();
		}
	}
}