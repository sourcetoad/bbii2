<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiSetting;
use frontend\modules\bbii\models\BbiiMembergroup;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiSpider;

use yii;
use yii\widgets\ActiveForm;
use yii\web\Controller;

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
				'actions'    => array(
					'getforum',
					'ajaxSort',
					'changeModerator',
					'deleteForum',
					'deleteMembergroup',
					'deleteSpider',
					'getMembergroup',
					'getSpider',
					'group',
					'index',
					'layout',
					'moderator',
					'saveForum',
					'saveMembergroup',
					'saveSpider',
					'spider',
				),
				'expression' => ($this->isAdmin())?'true':'false',
				'users'      => array('@'),
			),
			array('deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionIndex() {
		$model = BbiiSetting::find()->one();
		if ($model === null) {
			$model = new BbiiSetting();
		}

		if (isset(Yii::$app->request->post()['BbiiSetting'])) {
			$model->attributes = Yii::$app->request->post()['BbiiSetting'];
			if ($model->save()) {

				// @depricated 2.0.0
				//return Yii::$app->response->redirect(array('forum/index'));
				return Yii::$app->response->redirect(array(Yii::$app->requestedRoute));
			}
		}

		return $this->render('index', array('model' => $model));
	}

	/**
	 * [actionLayout description]
	 *
	 * @todo  This should be moved to the BbiiForm CNTL - DJE : 2015-05-20
	 * @return [type] [description]
	 */
	public function actionLayout() {
		$model    = new BbiiForum();

		if (Yii::$app->request->post('BbiiForum')) {
			$model->setAttributes(Yii::$app->request->post('BbiiForum'));

			if ($model->validate() && $model->save()) {

				Yii::$app->session->setFlash('success', Yii::t('BbiiModule.bbii', 'Operation successful.'));
			} else {

				Yii::$app->session->setFlash('error', Yii::t('BbiiModule.bbii', 'Operation failed.'));
			}
		}

		return $this->render('layout', array(
			'category' => BbiiForum::find()->sorted()->category()->all(),
			'model'    => $model,
		));
	}

	public function actionGroup() {
		$model = new BbiiMembergroup();
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMembergroup'])) {
			$model->attributes = $_GET['BbiiMembergroup'];
		}

		return $this->render('group', array('model' => $model));
	}

	public function actionModerator() {
		$model = new BbiiMember();
		$model = $model->search();

		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiMember'])) {
			$model->attributes = $_GET['BbiiMember'];
		}

		return $this->render('moderator',array(
			'model' => $model,
		));
	}

	public function actionSpider() {
		$model = new BbiiSpider();
		$model = $model->search();
		// $model->unsetAttributes();  // clear any default values
		if (isset($_GET['BbiiSpider']))
			$model->attributes = $_GET['BbiiSpider'];

		return $this->render('spider', array('model' => $model));
	}

	/**
	 * handle Ajax call for sorting categories and forums
	 */
	public function actionAjaxSort() {
		if (isset(Yii::$app->request->post()['cat'])) {
			$number = 1;
			foreach(Yii::$app->request->post()['cat'] as $id) {
				$model = BbiiForum::find($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes' => 'yes');
		} elseif (isset(Yii::$app->request->post()['frm'])) {
			$number = 1;
			foreach(Yii::$app->request->post()['frm'] as $id) {
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
	 *
	 * Method names in Yii2 can not have a 2nd capital letter. Only upper case the first letter of the first word after 'action' - DJE : 2015-05-21
	 */
	public function actionGetforum($id = null) {
		$id = ($id == null) ? Yii::$app->request->get('id') : $id;

		echo json_encode(
			(is_numeric($id))
			? BbiiForum::find()->where(['id' => $id])->asArray()->one()
			: ['error' => 'Unable to retrieve requested information.']
		);

		Yii::$app->end();
	}

	/**
	 * handle Ajax call for deleting forum
	 */
	public function actionDeleteForum() {
		$json = array();
		if (isset(Yii::$app->request->post('id'))) {
			$model = BbiiForum::find(Yii::$app->request->post('id'));
			if (BbiiForum::find()->exists("cat_id = " . Yii::$app->request->post('id'))) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still forums in this category. Remove these before deleting the category.');
			} elseif (BbiiTopic::find()->exists('forum_id = ' . Yii::$app->request->post('id'))) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still topics in this forum. Remove these before deleting the forum.');
			} else {
				BbiiForum::find(Yii::$app->request->post('id'))->delete();
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
		if (isset(Yii::$app->request->post()['BbiiForum'])) {
			$model = BbiiForum::find(Yii::$app->request->post()['BbiiForum']['id']);
			$model->attributes = Yii::$app->request->post()['BbiiForum'];
			if ($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(ActiveForm::validate($model));
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
		if (isset(Yii::$app->request->post('id'))) {
			if (Yii::$app->request->post('id') == 0) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'The default member group cannot be removed.');
			} else {
				BbiiMembergroup::find(Yii::$app->request->post('id'))->delete();
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
		if (isset(Yii::$app->request->post('id'))) {
			BbiiSpider::find(Yii::$app->request->post('id'))->delete();
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
		if (isset(Yii::$app->request->post()['BbiiMembergroup'])) {
			if (Yii::$app->request->post()['BbiiMembergroup']['id'] == '') {
				$model = new BbiiMembergroup;
			} else {
				$model = BbiiMembergroup::find(Yii::$app->request->post()['BbiiMembergroup']['id']);
			}
			$model->attributes = Yii::$app->request->post()['BbiiMembergroup'];
			if ($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(ActiveForm::validate($model));
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
		if (isset(Yii::$app->request->post()['BbiiSpider'])) {
			if (Yii::$app->request->post()['BbiiSpider']['id'] == '') {
				$model = new BbiiSpider;
			} else {
				$model = BbiiSpider::find(Yii::$app->request->post()['BbiiSpider']['id']);
			}
			$model->attributes = Yii::$app->request->post()['BbiiSpider'];
			if ($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(ActiveForm::validate($model));
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
		if (isset(Yii::$app->request->post('id')) && isset(Yii::$app->request->post()['moderator'])) {
			$model = BbiiMember::find(Yii::$app->request->post('id'));
			if ($model !== null) {
				$model->moderator = Html::encode(Yii::$app->request->post()['moderator']);
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
		if (isset(Yii::$app->request->post()['ajax']) && Yii::$app->request->post()['ajax'] === 'bbii-member-form')
		{
			echo ActiveForm::validate($model);
			Yii::$app->end();
		}
	}
}