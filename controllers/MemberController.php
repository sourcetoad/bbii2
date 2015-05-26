<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\components\BbiiTopicsRead;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiTopic;
use frontend\modules\bbii\models\BbiiTopicRead;

use yii;
use yii\data\ActiveDataProvider;
use yii\web\Serializer;
use yii\web\UploadedFile;

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
		$model = new BbiiMember('search');
		// No longer needed in Yii2+
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMember']))
			$model->attributes = $_GET['BbiiMember'];

		return $this->render('index', array('model' => $model));
	}
	
	public function actionUpdate($id) {
		$model = $this->loadModel($id)->one();

		if ($id != Yii::$app->user->id && !$this->isModerator()) {
			Yii::$app->user->setFlash('error', Yii::t('BbiiModule.bbii', 'Not Authorized'));
			return Yii::$app->response->redirect(array('member/index'));
		}

		if (isset(Yii::$app->request->post()['BbiiMember'])) {
			//$model->attributes = Yii::$app->request->post()['BbiiMember'];
			$model->setAttributes(Yii::$app->request->post('BbiiMember'));
			$model->image = UploadedFile::getInstance($model, 'image');

			if ($model->validate() && $model->save()) {

				$valid = true;
				if ($model->remove_avatar) {
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
				if ($valid)
					return Yii::$app->response->redirect(array('forum/member/view','id' => $model->id));
			} else {

				Yii::$app->user->setFlash('error',Yii::t('BbiiModule.bbii','Error while saving.'));
			}
		}

		return $this->render('update', array(
			'model' => $model
		));
	}
	
	/**
	 * [actionView description]
	 *
	 * @deprecated 2.2.0
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	/*public function actionView($id) {
		if (isset($_GET['unwatch']) && ($this->isModerator() || $id == Yii::$app->user->id)) {
			$object = new BbiiTopicsRead;
			$read = BbiiTopicRead::find($id);
			if ($read !== null) {
				$object->unserialize($read->data);
				foreach($_GET['unwatch'] as $topicId => $val) {
					$object->unsetFollow($topicId);
				}
				$read->data = $object->serialize();
				$read->save();
			}
		}
		$model = $this->loadModel($id);
		$dataProvider = new ActiveDataProvider('BbiiPost', array(
			'criteria' => array(
				'condition' => "approved = 1 and user_id = $id",
				'order' => 'create_time DESC',
				'with' => 'forum',
				'limit' => 10,
			),
			'pagination' => false,
		));
		if ($this->isModerator() || $id == Yii::$app->user->id) {
			$object = new BbiiTopicsRead;
			$read = BbiiTopicRead::find($id);
			if ($read === null) {
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
		
		return $this->render('view', array(
			'model' => $model, 
			'dataProvider' => $dataProvider,
			'topicProvider' => $topicProvider,
		));
	}*/

	/**
	 * [actionView description]
	 *
	 * @version  2.2.0
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionView($id) {
		$model  = $this->loadModel($id);
		$object = new BbiiTopicsRead;
		$read   = BbiiTopicRead::find($id);

		if (isset($_GET['unwatch']) && ($this->isModerator() || $id == Yii::$app->user->id)) {

			if ($read !== null) {
				$object->unserialize($read->data);
				foreach($_GET['unwatch'] as $topicId => $val) {
					$object->unsetFollow($topicId);
				}
				$read->data = $object->serialize();
				$read->save();
			}
		}
		if ( ($this->isModerator() || $id == Yii::$app->user->id) && isset($read->data) ) {
			if ($read === null) {

				$in = array(0);
			} else {
				$object->unserialize($read->data);
				$in = array_keys($object->getFollow());
			}
		} else {

				$in = array(0);
		}


		// @todo Need to figure out the Yii2 version of `'with' => 'forum',` for ADP - DJE : 2015-05-15
		$dataProvider = new ActiveDataProvider([
			'pagination' => false,
			'query'      => BbiiPost::find()->where(['approved' => 1, 'user_id' => $id])->orderBy('create_time DESC')->limit(10),
	    ]);

		// @todo Need to figure out the Yii2 version of `'with' => 'forum',` for ADP - DJE : 2015-05-15
		$topicProvider = new ActiveDataProvider([
			'pagination' => false,
			'query'      => BbiiTopic::find()->where(['id' => $in])->orderBy('id ASC')
		]);
		
		return $this->render('view', array(
			'dataProvider'  => $dataProvider,
			'model'         => $model, 
			'topicProvider' => $topicProvider,
		));
	}

	public function actionMail($id) {
		$model = new MailForm;
		if (isset(Yii::$app->request->post()['MailForm'])) {
			$model->attributes = Yii::$app->request->post()['MailForm'];
			if ($model->validate()) {
				$class = new $this->module->userClass;
				$criteria = new CDbCriteria;
				$criteria->condition = $this->module->userIdColumn . ' = :id';
				$criteria->params = array(':id' => Yii::$app->user->id);
				$user 	 =  $class::find()->find($criteria);
				$from 	 =  $user->getAttribute($this->module->userMailColumn);
				$criteria->params = array(':id' => $model->member_id);
				$user 	 =  $class::find()->find($criteria);
				$to 	 =  $user->getAttribute($this->module->userMailColumn);
				
				$name = BbiiMember::find(Yii::$app->user->id)->member_name;
				$name = ' = ?UTF-8?B?'.base64_encode($name).'? = ';
				$subject = ' = ?UTF-8?B?'.base64_encode($model->subject).'? = ';
				$sendto = $model->member_name . " <$to>";
				$headers = "From: $name <$from>\r\n".
					"To: {$sendto}\r\n".
					"Date: " . date(DATE_RFC2822) . "\r\n".
					"Reply-To: $from\r\n".
					"Message-ID: <" . uniqid('', true) . "@bbii.forum>\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/html; charset = UTF-8";

				mail($sendto,$subject,$model->body,$headers);
				Yii::$app->user->setFlash('notice',Yii::t('BbiiModule.bbii','You have sent an e-mail to {member_name}.', array('{member_name}' => $model->member_name)));
				
				return Yii::$app->response->redirect(array('forum/view','id' => $model->member_id));
			}
		} else {
			$model->member_id = $id;
			$model->member_name = BbiiMember::find($id)->member_name;
		}
		return $this->render('mail',array('model' => $model));
	}
	
	/**
	 * Ajax for auto-complete search
	 */
	public function actionMembers() {
		$json = array();
		if (isset($_GET['term'])) {
			$criteria = new CDbCriteria;
			$criteria->compare('member_name',$_GET['term'],true);
			$criteria->limit = 15;
			$models = BbiiMember::find()->findAll($criteria);
			foreach($models as $model) {
				$json[] = array('value' => $model->id,'label' => $model->member_name);
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	public function actionWatch() {
		if ($this->module->dbName === false) {
			$db = 'db';
		} else {
			$db = $this->module->dbName;
		}
		$class = new $this->module->userClass;
		$table = $class::find()->tableName();
		$obj = new BbiiWatcherMail(
			$this->context->module->forumTitle,
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
		$model = BbiiMember::find($id);
		if ($model === null)
			throw new HttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BbiiMembergroup $model the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset(Yii::$app->request->post()['ajax']) && Yii::$app->request->post()['ajax'] === 'bbii-member-form')
		{
			echo ActiveForm::validate($model);
			Yii::$app->end();
		}
	}
	
	private function resizeImage($filename, $targetname, $location) {
		$extension = substr($filename, -3);
		switch($extension) {
			case 'gif':
				$image = @imagecreatefromgif ($location . $filename);
				break;
			case 'jpg':
				$image = @imagecreatefromjpeg($location . $filename);
				break;
			case 'png':
				$image = @imagecreatefrompng($location . $filename);
				break;
		}
		if ($image) {
			$width = imagesx($image);
			$height = imagesy($image);
			// medium
			if ($width > 90 || $height > 90) {
				$wr = $width/90;
				$hr = $height/90;
				if ($wr > $hr) {
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
			if ($filename != $targetname) {
				unlink($location . $filename);
			}
		} else {
			unlink($location . $filename);
		}
	}
}