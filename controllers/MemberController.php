<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiMessage;

use Yii;
use yii\web\User;

class MemberController extends BbiiController {
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
				'actions' => array('index','mail','members','view','update'),
				'users' => array('@'),
			),
			array('allow',
				'actions' => array('watch'),
				'users' => array('*'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}
	
	public function actionIndex() {
		$model = new BbiiMember;

		if(isset(Yii::$app->request->get()['BbiiMember'])) {
			$model->attributes=Yii::$app->request->get()['BbiiMember'];
		}


		return $this->render('index', array(
			'is_admin' => $this->isModerator(),
			'is_mod'   => $this->isAdmin(),
			'messages' => BbiiMessage::find()->count('sendto = '.Yii::$app->user->id),
			'model'    => $model,
		));
	}
	
	public function actionUpdate($id) {
		$model=$this->loadModel($id);
		
		if($id != Yii::$app->user->id && !$this->isModerator()) {
			throw new CHttpException(403, Yii::t('yii', 'You are not authorized to perform this action.'));
		}
		
		if(isset($_POST['BbiiMember'])) {
			$model->attributes=$_POST['BbiiMember'];
			$model->image = CUploadedFile::getInstance($model, 'image');
			if($model->save()) {
				$valid = true;
				if($model->remove_avatar) {
					$model->avatar = '';
					$model->save();
				} else {
					if ($model->image !== null) {
						$location = Yii::getPathOfAlias('webroot') . $this->module->avatarStorage . '/';
						$location = str_replace('/', DIRECTORY_SEPARATOR, $location);
						$filename = uniqid('img');
						$model->avatar = uniqid('img') . '.jpg';
						switch( exif_imagetype($model->image->tempName) ) {
							case IMAGETYPE_GIF:
								$filename .= '.gif';
								$model->image->saveAs($location . $filename);
								$model->save();
								$this->resizeImage($filename, $model->avatar, $location);
								break;
							case IMAGETYPE_JPEG:
								$filename .= '.jpg';
								$model->image->saveAs($location . $filename);
								$model->save();
								$this->resizeImage($filename, $model->avatar, $location);
								break;
							case IMAGETYPE_PNG:
								$filename .= '.png';
								$model->image->saveAs($location . $filename);
								$model->save();
								$this->resizeImage($filename, $model->avatar, $location);
								break;
							default:
								$model->addError('images', Yii::t('app', 'The file {filename} cannot be uploaded. Only files with the image formats gif, jpg or png can be uploaded.', array('{filename}' => $model->image->name)));
								$valid = false;
								break;
						}
					}
				}
				if($valid)
					$this->redirect(array('view','id' => $model->id));
			}
		}
		$this->render('update', array('model' => $model));
	}
	
	public function actionView($id) {
		if(isset(Yii::$app->request->get()['unwatch']) && ($this->isModerator() || $id == Yii::$app->user->id)) {
			$object = new BbiiTopicsRead;
			$read = BbiiTopicRead::model()->findByPk($id);
			if($read !== null) {
				$object->unserialize($read->data);
				foreach(Yii::$app->request->get()['unwatch'] as $topicId => $val) {
					$object->unsetFollow($topicId);
				}
				$read->data = $object->serialize();
				$read->save();
			}
		}
		$model=$this->loadModel($id);
		$dataProvider = new ActiveDataProvider('BbiiPost', array(
			'criteria' => array(
				'condition' => "approved = 1 and user_id = $id",
				'order' => 'create_time DESC',
				'with' => 'forum',
				'limit' => 10,
			),
			'pagination' => false,
		));
		if($this->isModerator() || $id == Yii::$app->user->id) {
			$object = new BbiiTopicsRead;
			$read = BbiiTopicRead::model()->findByPk($id);
			if($read === null) {
				$in = array(0);
			} else {
				$object->unserialize($read->data);
				$in = array_keys($object->getFollow());
			}
		} else {
				$in = array(0);
		}
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $in);
		$criteria->order = 'id';
		$topicProvider = new ActiveDataProvider('BbiiTopic', array(
			'criteria' => $criteria,
			'pagination' => false,
		));
		
		$this->render('view', array(
			'model' => $model, 
			'dataProvider' => $dataProvider,
			'topicProvider' => $topicProvider,
		));
	}
	
	public function actionMail($id) {
		$model=new MailForm;
		if(isset($_POST['MailForm'])) {
			$model->attributes=$_POST['MailForm'];
			if($model->validate()) {
				$class = new $this->module->userClass;
				$criteria = new CDbCriteria;
				$criteria->condition = $this->module->userIdColumn . '=:id';
				$criteria->params = array(':id' => Yii::$app->user->id);
				$user 	= $class::model()->find($criteria);
				$from 	= $user->getAttribute($this->module->userMailColumn);
				$criteria->params = array(':id' => $model->member_id);
				$user 	= $class::model()->find($criteria);
				$to 	= $user->getAttribute($this->module->userMailColumn);
				
				$name = BbiiMember::model()->findByPk(Yii::$app->user->id)->member_name;
				$name='=?UTF-8?B?'.base64_encode($name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$sendto = $model->member_name . " <$to>";
				$headers="From: $name <$from>\r\n".
					"To: {$sendto}\r\n".
					"Date: " . date(DATE_RFC2822) . "\r\n".
					"Reply-To: $from\r\n".
					"Message-ID: <" . uniqid('', true) . "@bbii.forum>\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/html; charset=UTF-8";

				mail($sendto,$subject,$model->body,$headers);
				Yii::$app->user->setFlash('notice',Yii::t('BbiiModule.bbii','You have sent an e-mail to {member_name}.', array('{member_name}' => $model->member_name)));
				
				$this->redirect(array('view','id' => $model->member_id));
			}
		} else {
			$model->member_id = $id;
			$model->member_name = BbiiMember::model()->findByPk($id)->member_name;
		}
		$this->render('mail',array('model' => $model));
	}
	
	/**
	 * Ajax for auto-complete search
	 */
	public function actionMembers() {
		$json = array();
		if(isset(Yii::$app->request->get()['term'])) {
			$criteria = new CDbCriteria;
			$criteria->compare('member_name',Yii::$app->request->get()['term'],true);
			$criteria->limit = 15;
			$models = BbiiMember::model()->findAll($criteria);
			foreach($models as $model) {
				$json[] = array('value' => $model->id,'label' => $model->member_name);
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	public function actionWatch() {
		if($this->module->dbName === false) {
			$db = 'db';
		} else {
			$db = $this->module->dbName;
		}
		$class = new $this->module->userClass;
		$table = $class::model()->tableName();
		$obj = new BbiiWatcherMail(
			$this->module->forumTitle,
			$db,
			$this->module->userClass,
			$table,
			$this->module->userIdColumn,
			$this->module->userNameColumn,
			$this->module->userMailColumn
		);
		$obj->processWatchers();
		echo 'Complete';
		Yii::$app->end();
	}
	
	public function loadModel($id) {
		$model=BbiiMember::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BbiiMembergroup $model the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='bbii-member-form')
		{
			echo CActiveForm::validate($model);
			Yii::$app->end();
		}
	}
	
	private function resizeImage($filename, $targetname, $location) {
		$extension = substr($filename, -3);
		switch($extension) {
			case 'gif':
				$image = @imagecreatefromgif($location . $filename);
				break;
			case 'jpg':
				$image = @imagecreatefromjpeg($location . $filename);
				break;
			case 'png':
				$image = @imagecreatefrompng($location . $filename);
				break;
		}
		if($image) {
			$width = imagesx($image);
			$height = imagesy($image);
			// medium
			if($width > 90 || $height > 90) {
				$wr = $width/90;
				$hr = $height/90;
				if($wr > $hr) {
					$ratio = $wr;
				} else {
					$ratio = $hr;
				}
				$dest_w = (int) ($width/$ratio);
				$dest_h = (int) ($height/$ratio);
			} else {
				$dest_w = $width;
				$dest_h = $height;
			}
			$destImage = imagecreatetruecolor ($dest_w, $dest_h);
			imagecopyresampled($destImage, $image, 0, 0, 0, 0, $dest_w, $dest_h, $width, $height);
			imagejpeg($destImage, $location . $targetname, 85);
			if($filename != $targetname) {
				unlink($location . $filename);
			}
		} else {
			unlink($location . $filename);
		}
	}
}