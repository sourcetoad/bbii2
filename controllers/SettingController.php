<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiSetting;

use yii;
use yii\widgets\ActiveForm;

class SettingController extends BbiiController {

	/**
	 * [init description]
	 *
	 * @deprecated 2.2.0
	 * @return [type] [description]
	 */
	public function init() {
		//Yii::$app->clientScript->registerScriptFile($this->module->getAssetsUrl() . '/js/bbiiSetting.js', CClientScript::POS_HEAD);
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
				'actions'    => array('ajaxSort','deleteForum','deleteMembergroup','getForum','getMembergroup','saveForum','saveMembergroup','group','index','layout','spider','getSpider','deleteSpider','saveSpider','moderator','changeModerator'),
				'expression' => ($this->isAdmin())?'true':'false',
				'users'      => array('@'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex() {
		// Alwasy return
		// $model = BbiiSetting::find();
		// if ($model === null) {
			$model = new BbiiSetting;
		// }
		
		if (isset($_POST['BbiiSetting'])) {
			$model->attributes = $_POST['BbiiSetting'];
			if ($model->save())
				$this->redirect(array('index'));
		}

		return $this->render('index', array('model' => $model));
	}
		
	public function actionLayout() {
		$category = BbiiForum::find()->sorted()->category()->all();
		$forum    = array();
		$model    = new BbiiForum;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['BbiiForum'])) {
			$model->attributes = $_POST['BbiiForum'];
			if ($model->save()) {
				$this->redirect(array('layout'));
			}
		}
		
		return $this->render('layout', array(
			'model' => $model,
			'category' => $category,
		));
	}

	public function actionGroup() {
		$model = new BbiiMembergroup('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMembergroup']))
			$model->attributes = $_GET['BbiiMembergroup'];

		return $this->render('group', array('model' => $model));
	}

	public function actionModerator() {
		$model = new BbiiMember('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMember']))
			$model->attributes = $_GET['BbiiMember'];

		return $this->render('moderator',array(
			'model' => $model,
		));
	}

	public function actionSpider() {
		$model = new BbiiSpider('search');
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiSpider']))
			$model->attributes = $_GET['BbiiSpider'];

		return $this->render('spider', array('model' => $model));
	}

	/**
	 * handle Ajax call for sorting categories and forums
	 */
	public function actionAjaxSort() {
		if (isset($_POST['cat'])) {
			$number = 1;
			foreach($_POST['cat'] as $id) {
				$model = BbiiForum::find($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes' => 'yes');
		} elseif (isset($_POST['frm'])) {
			$number = 1;
			foreach($_POST['frm'] as $id) {
				$model = BbiiForum::find($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes' => 'yes');
		} else { 
			$json = array('succes' => 'no');
		}
		echo json_encode($json);
		Yii::$app->end();
	}

	/**
	 * handle Ajax call for getting forum
	 */
	public function actionGetForum() {
		$json = array();
		if (isset($_GET['id'])) {
			$model = BbiiForum::find($_GET['id']);
			if ($model !== null) {
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
		if (isset($_POST['id'])) {
			$model = BbiiForum::find($_POST['id']);
			if (BbiiForum::find()->exists("cat_id = " . $_POST['id'])) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still forums in this category. Remove these before deleting the category.');
			} elseif (BbiiTopic::find()->exists('forum_id = ' . $_POST['id'])) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still topics in this forum. Remove these before deleting the forum.');
			} else {
				BbiiForum::find($_POST['id'])->delete();
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
		if (isset($_POST['BbiiForum'])) {
			$model = BbiiForum::find($_POST['BbiiForum']['id']);
			$model->attributes = $_POST['BbiiForum'];
			if ($model->save()) {
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
		if (isset($_GET['id'])) {
			$model = BbiiMembergroup::find($_GET['id']);
			if ($model !== null) {
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
		if (isset($_GET['id'])) {
			$model = BbiiSpider::find($_GET['id']);
			if ($model !== null) {
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
		if (isset($_POST['id'])) {
			if ($_POST['id'] == 0) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'The default member group cannot be removed.');
			} else {
				BbiiMembergroup::find($_POST['id'])->delete();
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
		if (isset($_POST['id'])) {
			BbiiSpider::find($_POST['id'])->delete();
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
		if (isset($_POST['BbiiMembergroup'])) {
			if ($_POST['BbiiMembergroup']['id'] == '') {
				$model = new BbiiMembergroup;
			} else {
				$model = BbiiMembergroup::find($_POST['BbiiMembergroup']['id']);
			}
			$model->attributes = $_POST['BbiiMembergroup'];
			if ($model->save()) {
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
		if (isset($_POST['BbiiSpider'])) {
			if ($_POST['BbiiSpider']['id'] == '') {
				$model = new BbiiSpider;
			} else {
				$model = BbiiSpider::find($_POST['BbiiSpider']['id']);
			}
			$model->attributes = $_POST['BbiiSpider'];
			if ($model->save()) {
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
		if (isset($_POST['id']) && isset($_POST['moderator'])) {
			$model = BbiiMember::find($_POST['id']);
			if ($model !== null) {
				$model->moderator = Html::encode($_POST['moderator']);
				$model->save();
				$json['success'] = true;
			}
		}
		echo json_encode($json);
		Yii::$app->end();
	}

	public function loadModel($id) {
		$model = BbiiMember::find($id);
		if ($model === null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BbiiForum $model the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'bbii-member-form')
		{
			echo CActiveForm::validate($model);
			Yii::$app->end();
		}
	}
}