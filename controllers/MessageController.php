<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\components\BbiiController;


use yii;
use yii\web\Session;

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
		$model->inbox  = 1;
		*/

		$id = ($id != null) ? $id : Yii::$app->user->id;

		$model           = new BbiiMessage();
		$model->setAttributes( (isset($_GET['BbiiMessage']) ? $_GET['BbiiMessage'] : ['inbox' => 1, 'sendto' => $id]) );
		$model           = $model->search();

		return $this->render('inbox', array(
			'model' => $model, 
			'count' => $this->getMessageCount(),
		));
	}

	public function actionOutbox($id = null) {
		if (!(isset($id) && $this->isModerator())) {
			$id = Yii::$app->user->id;
		}

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
			'count' => $this->getMessageCount(),
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
		$count['inbox']  = BbiiMessage::find()->inbox()->count('sendto = '.Yii::$app->user->id);
		$count['outbox'] = BbiiMessage::find()->outbox()->count('sendfrom = '.Yii::$app->user->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (Yii::$app->request->post('BbiiMessage')) {
			$model->load(Yii::$app->request->post('BbiiMessage'));
			//$model->search = Yii::$app->request->post()['BbiiMessage']['search'];
			//$model->sendfrom = Yii::$app->user->id;

			if ($model->validate() && empty(Yii::$app->request->post('BbiiMessage')['search'])) {
				unset($model->sendto);
			} else {
				// $criteria = new CDbCriteria;
				// $criteria->condition = 'member_name = :search';
				// $criteria->params = array(':search' => Yii::$app->request->post()['BbiiMessage']['search']);
				// $member = BbiiMember::find()->find($criteria);
				$member = BbiiMember::find()->where(['member_name' => Yii::$app->request->post('BbiiMessage')['search']])->one();
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
	}*/ 

	/**
	 * [actionCreate description]
	 *
	 * @version  2.5.0
	 * @param  [type] $id   [description]
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function actionCreate($id = null, $type = 1) {
		$model = new BbiiMessage;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (Yii::$app->request->post('BbiiMessage')) {
			// automatic attrib set
			$model->setAttributes(Yii::$app->request->post('BbiiMessage'));
			
			// manual sttrib set
			$model->sendfrom = Yii::$app->user->id;
			$model->sendto = BbiiMember::find()->select('id')->where([
				'member_name' => Yii::$app->request->post('BbiiMessage')['sendto']
			])->one()->getAttribute('id');
			$model->type = $type;

			if ($model->validate() && $model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('BbiiModule.bbii', 'Message sent successfully.'));
				return Yii::$app->response->redirect(array('forum/message/layout'));
			} else {

				Yii::$app->session->setFlash('error',Yii::t('BbiiModule.bbii', 'Could not send message.'));
				return Yii::$app->response->redirect(array('forum/message/layout'));
			}

		} elseif (isset($id)) {
			// @todo No idea what this does yet - DJE : 2015-05-20
			$model->sendto = $id;
			$model->search = $model->receiver->member_name;
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
	public function actionReply($id) {
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
			'count' => $this->getMessageCount(),
		));
	}
	
	public function actionDelete($id) {
		$model = $this->getMessageMDL($id);

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
		if (!isset($_GET['ajax'])) {

			return Yii::$app->response->redirect(
				isset(Yii::$app->request->post()['returnUrl'])
				? Yii::$app->request->post()['returnUrl']
				: ['forum/message/inbox']
			);
		}
	}
	
	/**
	 * handle Ajax call for viewing message
	 *
	 * @deprecated 2.5.0 VIEW is no longer json only response
	 */
	/*public function actionView() {
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
	}*/
	
	/**
	 * Display message contents
	 * 
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionView($id) {
		$model = $this->getMessageMDL($id);

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
			echo ActiveForm::validate($model);
			Yii::$app->end();
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
			'inbox'  => BbiiMessage::find()->inbox()->count('inbox = 1 and sendto = '.Yii::$app->user->id),
			'outbox' => BbiiMessage::find()->outbox()->count('outbox = 1 and sendfrom = '.Yii::$app->user->id)
		];
	}

	/**
	 * [getMessageID description]
	 * @param  [type] $param [description]
	 * @return [type]        [description]
	 */
	private function getMessageID($param = null) {

		if (is_numeric($param)) { return $param; }

		$param = Yii::$app->request->post('BbiiMessage')['id'];
		if (is_numeric($param)) {
			return $param;
		}

		$param = Yii::$app->request->get('id');
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