<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;

class SettingController extends BbiiController {
	public function init() {
		Yii::$app->clientScript->registerScriptFile($this->module->getAssetsUrl() . '/js/bbiiSetting.js', CClientScript::POS_HEAD);
	}

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
				'actions'=>array('ajaxSort','deleteForum','deleteMembergroup','getForum','getMembergroup','saveForum','saveMembergroup','group','index','layout','spider','getSpider','deleteSpider','saveSpider','moderator','changeModerator'),
				'users'=>array('@'),
				'expression'=>($this->isAdmin())?'true':'false',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex() {
		$model = BbiiSetting::find()->find();
		if($model === null) {
			$model = new BbiiSetting;
		}
		
		if(isset($_POST['BbiiSetting'])) {
			$model->attributes=$_POST['BbiiSetting'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('index', array('model'=>$model));
	}
		
	public function actionLayout() {
		$model=new BbiiForum;
		$forum = array();
		$category = BbiiForum::find()->sorted()->category()->findAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BbiiForum'])) {
			$model->attributes=$_POST['BbiiForum'];
			if($model->save()) {
				$this->redirect(array('layout'));
			}
		}
		
		$this->render('layout', array(
			'model'=>$model,
			'category'=>$category,
		));
	}
	
	public function actionGroup() {
		$model=new BbiiMembergroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BbiiMembergroup']))
			$model->attributes=$_GET['BbiiMembergroup'];

		$this->render('group', array('model'=>$model));
	}
	
	public function actionModerator() {
		$model=new BbiiMember('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BbiiMember']))
			$model->attributes=$_GET['BbiiMember'];

		$this->render('moderator',array(
			'model'=>$model,
		));
	}
	
	public function actionSpider() {
		$model=new BbiiSpider('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BbiiSpider']))
			$model->attributes=$_GET['BbiiSpider'];

		$this->render('spider', array('model'=>$model));
	}
	
	/**
	 * handle Ajax call for sorting categories and forums
	 */
	public function actionAjaxSort() {
		if(isset($_POST['cat'])) {
			$number = 1;
			foreach($_POST['cat'] as $id) {
				$model = BbiiForum::find()->findByPk($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes'=>'yes');
		} elseif(isset($_POST['frm'])) {
			$number = 1;
			foreach($_POST['frm'] as $id) {
				$model = BbiiForum::find()->findByPk($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes'=>'yes');
		} else { 
			$json = array('succes'=>'no');
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for getting forum
	 */
	public function actionGetForum() {
		$json = array();
		if(isset($_GET['id'])) {
			$model = BbiiForum::find()->findByPk($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['subtitle'] = $model->subtitle;
				$json['cat_id'] = $model->cat_id;
				$json['type'] = $model->type;
				$json['locked'] = $model->locked;
				$json['public'] = $model->public;
				$json['moderated'] = $model->moderated;
				$json['membergroup_id'] = $model->membergroup_id;
				$json['poll'] = $model->poll;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for deleting forum
	 */
	public function actionDeleteForum() {
		$json = array();
		if(isset($_POST['id'])) {
			$model = BbiiForum::find()->findByPk($_POST['id']);
			if(BbiiForum::find()->exists("cat_id = " . $_POST['id'])) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still forums in this category. Remove these before deleting the category.');
			} elseif(BbiiTopic::find()->exists('forum_id = ' . $_POST['id'])) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still topics in this forum. Remove these before deleting the forum.');
			} else {
				BbiiForum::find()->findByPk($_POST['id'])->delete();
				$json['success'] = 'yes';
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for saving forum
	 */
	public function actionSaveForum() {
		$json = array();
		if(isset($_POST['BbiiForum'])) {
			$model = BbiiForum::find()->findByPk($_POST['BbiiForum']['id']);
			$model->attributes=$_POST['BbiiForum'];
			if($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(CActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for getting membergroup
	 */
	public function actionGetMembergroup() {
		$json = array();
		if(isset($_GET['id'])) {
			$model = BbiiMembergroup::find()->findByPk($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['description'] = $model->description;
				$json['min_posts'] = $model->min_posts;
				$json['color'] = $model->color;
				$json['image'] = $model->image;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for getting spider
	 */
	public function actionGetSpider() {
		$json = array();
		if(isset($_GET['id'])) {
			$model = BbiiSpider::find()->findByPk($_GET['id']);
			if($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['user_agent'] = $model->user_agent;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for deleting membergroup
	 */
	public function actionDeleteMembergroup() {
		$json = array();
		if(isset($_POST['id'])) {
			if($_POST['id'] == 0) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'The default member group cannot be removed.');
			} else {
				BbiiMembergroup::find()->findByPk($_POST['id'])->delete();
				$json['success'] = 'yes';
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for deleting spider
	 */
	public function actionDeleteSpider() {
		$json = array();
		if(isset($_POST['id'])) {
			BbiiSpider::find()->findByPk($_POST['id'])->delete();
			$json['success'] = 'yes';
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for saving membergroup
	 */
	public function actionSaveMembergroup() {
		$json = array();
		if(isset($_POST['BbiiMembergroup'])) {
			if($_POST['BbiiMembergroup']['id'] == '') {
				$model = new BbiiMembergroup;
			} else {
				$model = BbiiMembergroup::find()->findByPk($_POST['BbiiMembergroup']['id']);
			}
			$model->attributes=$_POST['BbiiMembergroup'];
			if($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(CActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for saving spider
	 */
	public function actionSaveSpider() {
		$json = array();
		if(isset($_POST['BbiiSpider'])) {
			if($_POST['BbiiSpider']['id'] == '') {
				$model = new BbiiSpider;
			} else {
				$model = BbiiSpider::find()->findByPk($_POST['BbiiSpider']['id']);
			}
			$model->attributes=$_POST['BbiiSpider'];
			if($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(CActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	/**
	 * handle Ajax call for changing moderator
	 */
	public function actionChangeModerator() {
		$json = array();
		if(isset($_POST['id']) && isset($_POST['moderator'])) {
			$model = BbiiMember::find()->findByPk($_POST['id']);
			if($model !== null) {
				$model->moderator = CHtml::encode($_POST['moderator']);
				$model->save();
				$json['success'] = true;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}
	
	public function loadModel($id) {
		$model=BbiiMember::find()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param BbiiForum $model the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if(isset($_POST['ajax']) && $_POST['ajax']==='bbii-member-form')
		{
			echo CActiveForm::validate($model);
			Yii::$app->end();
		}
	}
}