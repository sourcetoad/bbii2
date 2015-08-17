<?php

namespace frontend\modules\bbii\controllers;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiSetting;
use frontend\modules\bbii\models\BbiiMembergroup;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiSpider;
use frontend\modules\bbii\models\BbiiTopic;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\Controller;
use yii\web\User;
use yii\filters\AccessControl;

class SettingController extends BbiiController {

	/**
	 * [init description]
	 *
	 * @deprecated 2.2.0
	 * @return [type] [description]
	 */
	public function init() {
		//\Yii::$app->clientScript->registerScriptFile($this->module->getAssetsUrl() . '/js/bbiiSetting.js', CClientScript::POS_HEAD);
	}

	/**
	 * @deprecated 3.0.6fd6d72
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return false;
		/* return array(
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
					'index',
					'layout',
					'moderator',
					'update',
					'saveForum',
					'saveMembergroup',
					'saveSpider',
					'spider',

					'updateForum',

					'createmembergroup',
					'updateMembergroup',

					'createspider',
					'updatespider'
				),
				'expression' => ($this->isAdmin())?'true':'false',
				'users'      => array('@'),
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
						'actions'       => [
							'ajaxsort',
							'changemoderator',
							'createmembergroup',
							'createspider',
							'deleteforum',
							'deletemembergroup',
							'deletespider',
							'getforum',
							'getmembergroup',
							'getspider',
							'group',
							'index',
							'layout',
							'moderator',
							'saveforum',
							'savemembergroup',
							'savespider',
							'spider',
							'update',
							'updateforum',
							'updatemembergroup',
							'updatespider',
						],
						'allow'         => true,
						'matchCallback' => function() {
							 if ($this->isModerator() || $this->isAdmin()) {
							 	return true;
							 }

							 return false;
						},
                    ],
                ],
            ],
        ];
    }

	/**
	 * [actionIndex description]
	 *
	 * @version  3.0
	 * @return [type] [description]
	 */
	public function actionIndex() {
		$model = BbiiSetting::find()->one() ?: new BbiiSetting();;

		if (\Yii::$app->request->post()['BbiiSetting']) {

			$model->load(\Yii::$app->request->post());
			if ($model->validate() && $model->save()) {

				\Yii::$app->session->addFlash('success', Yii::t('BbiiModule.bbii', 'Change successful.'));
			} 
		}

		return $this->render('index', array(
			'model' => $model
		));
	}

	/**
	 * [actionLayout description]
	 *
	 * @todo  This should be moved to the BbiiForm CNTL - DJE : 2015-05-20
	 * @return [type] [description]
	 */
	public function actionLayout() {
		$model = new BbiiForum();

		if (\Yii::$app->request->post('BbiiForum')) {
			$model->setAttributes(\Yii::$app->request->post('BbiiForum'));

			if ($model->validate() && $model->save()) {

				\Yii::$app->session->addFlash('success', Yii::t('BbiiModule.bbii', 'Change successful.'));
			} else {

				\Yii::$app->session->addFlash('warning', Yii::t('BbiiModule.bbii', 'Change failed.'));
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
		if (isset(\Yii::$app->request->get()['BbiiMembergroup'])) {
			$model->load(\Yii::$app->request->get()['BbiiMembergroup']);
		}

		return $this->render('group', array('model' => $model));
	}

	public function actionModerator() {
		$model = new BbiiMember();
		$model = $model->search();

		// $model->unsetAttributes();  // clear any default values
		if (isset(\Yii::$app->request->get()['BbiiMember'])) {
			$model->load(\Yii::$app->request->get()['BbiiMember']);
		}

		return $this->render('moderator',array(
			'model' => $model,
		));
	}

	public function actionSpider() {
		$model = new BbiiSpider();
		$model = $model->search();
		// $model->unsetAttributes();  // clear any default values
		if (isset(\Yii::$app->request->get()['BbiiSpider']))
			$model->load(\Yii::$app->request->get()['BbiiSpider']);

		return $this->render('spider', array('model' => $model));
	}

	/**
	 * handle Ajax call for sorting categories and forums
	 */
	public function actionAjaxsort() {
		if (isset(\Yii::$app->request->post()['cat'])) {
			$number = 1;
			foreach(\Yii::$app->request->post()['cat'] as $id) {
				$model = BbiiForum::find($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes' => 'yes');
		} elseif (isset(\Yii::$app->request->post()['frm'])) {
			$number = 1;
			foreach(\Yii::$app->request->post()['frm'] as $id) {
				$model = BbiiForum::find($id);
				$model->sort = $number++;
				$model->save();
			}
			$json = array('succes' => 'yes');
		} else { 
			$json = array('succes' => 'no');
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for getting forum
	 *
	 * Method names in Yii2 can not have a 2nd capital letter. Only upper case the first letter of the first word after 'action' - DJE : 2015-05-21
	 */
	public function actionGetforum($id = null) {
		$id = ($id == null) ? \Yii::$app->request->get('id') : $id;

		echo json_encode(
			(is_numeric($id))
			? BbiiForum::find()->where(['id' => $id])->asArray()->one()
			: ['error' => 'Unable to retrieve requested information.']
		);

		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for deleting forum
	 */
	public function actionDeleteforum() {
		$json = array();
		if (isset(\Yii::$app->request->post()['id'])) {
			$model = BbiiForum::find(\Yii::$app->request->post()['id']);
			if (BbiiForum::find()->exists("cat_id = " . \Yii::$app->request->post()['id'])) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still forums in this category. Remove these before deleting the category.');
			} elseif (BbiiTopic::find()->exists('forum_id = ' . \Yii::$app->request->post()['id'])) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'There are still topics in this forum. Remove these before deleting the forum.');
			} else {
				BbiiForum::find(\Yii::$app->request->post()['id'])->delete();
				$json['success'] = 'yes';
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for saving forum
	 */
	public function actionSaveforum() {
		$json = array();
		if (\Yii::$app->request->post('BbiiForum')) {
			$model = BbiiForum::find(\Yii::$app->request->post('BbiiForum')['id']);
			$model->load(\Yii::$app->request->post()['BbiiForum']);
			if ($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(ActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for getting membergroup
	 */
	public function actionGetMembergroup() {
		$json = array();
		if (isset(\Yii::$app->request->get()['id'])) {
			$model = BbiiMembergroup::find(\Yii::$app->request->get()['id']);
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
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for getting spider
	 */
	public function actionGetspider() {
		$json = array();
		if (isset(\Yii::$app->request->get()['id'])) {
			$model = BbiiSpider::find(\Yii::$app->request->get()['id']);
			if ($model !== null) {
				$json['id'] = $model->id;
				$json['name'] = $model->name;
				$json['user_agent'] = $model->user_agent;
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for deleting membergroup
	 */
	public function actionDeletemembergroup() {
		$json = array();
		if (isset(\Yii::$app->request->post()['id'])) {
			if (\Yii::$app->request->post()['id'] == 0) {
				$json['success'] = 'no';
				$json['message'] = Yii::t('BbiiModule.bbii', 'The default member group cannot be removed.');
			} else {
				BbiiMembergroup::find(\Yii::$app->request->post()['id'])->delete();
				$json['success'] = 'yes';
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for deleting spider
	 */
	public function actionDeletespider() {
		$json = array();
		if (isset(\Yii::$app->request->post()['id'])) {
			BbiiSpider::find(\Yii::$app->request->post()['id'])->delete();
			$json['success'] = 'yes';
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for saving membergroup
	 */
	public function actionSavemembergroup() {
		$json = array();
		if (isset(\Yii::$app->request->post()['BbiiMembergroup'])) {
			if (\Yii::$app->request->post()['BbiiMembergroup']['id'] == '') {
				$model = new BbiiMembergroup;
			} else {
				$model = BbiiMembergroup::find(\Yii::$app->request->post()['BbiiMembergroup']['id']);
			}
			$model->load(\Yii::$app->request->post()['BbiiMembergroup']);
			if ($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(ActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for saving spider
	 */
	public function actionSavespider() {
		$json = array();
		if (isset(\Yii::$app->request->post()['BbiiSpider'])) {
			if (\Yii::$app->request->post()['BbiiSpider']['id'] == '') {
				$model = new BbiiSpider;
			} else {
				$model = BbiiSpider::find(\Yii::$app->request->post()['BbiiSpider']['id']);
			}
			$model->load(\Yii::$app->request->post()['BbiiSpider']);
			if ($model->save()) {
				$json['success'] = 'yes';
			} else {
				$json['error'] = json_decode(ActiveForm::validate($model));
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	/**
	 * handle Ajax call for changing moderator
	 */
	public function actionChangemoderator() {
		$json = array();
		if (isset(\Yii::$app->request->post()['id']) && isset(\Yii::$app->request->post()['moderator'])) {
			$model = BbiiMember::find(\Yii::$app->request->post()['id']);
			if ($model !== null) {
				$model->moderator = Html::encode(\Yii::$app->request->post()['moderator']);
				$model->save();
				$json['success'] = true;
			}
		}
		echo json_encode($json);
		\Yii::$app->end();
	}

	public function loadModel($id = null) {
		$model = BbiiMember::find($id);
		if ($model === null)
			throw new HttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BbiiForum $model the model to be validated
	 */
	protected function performAjaxvalidation($model) {
		if (isset(\Yii::$app->request->post()['ajax']) && \Yii::$app->request->post()['ajax'] === 'bbii-member-form')
		{
			echo ActiveForm::validate($model);
			\Yii::$app->end();
		}
	}



	// Yii2 CRUD style method boilerplate methods



	/**
	 * [update description]
	 *
	 * This is the way we should be writing the CRUD methods for V3
	 * 
	 * @version 3.0.5
	 * @since  2.7.0
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
    public function actionUpdateforum($id = null) {
        $model = BbiiForum::find()
        	->where(['id' => (int)($id ?: \Yii::$app->request->get('id'))])
        	->one();

        if (\Yii::$app->request->post('BbiiForum') && $model->load(\Yii::$app->request->post())) {
        	$success = ($model->validate() && $model->save()) ? true : false;

			\Yii::$app->getSession()->setFlash(
				(($success) ? 'success' : 'warning'),
				Yii::t('BbiiModule.bbii', 'Change '.(($success) ? '' : 'NOT').'saved.')
			);
        }

        return $this->render('update/forum', [
            'model' => $model,
        ]);
    }

    /**
     * [actionUpdate description]
     *
     * @todo  We can use this update() for updating just about anything that has a related MDL. Simply pass the ID and
     * the Name and it will redirect to a setting/update?id=X for the object.
     * 
     * @param  [type] $id        [description]
     * @param  [type] $type      [description]
     * @return [type]            [description]
     */
    public function actionUpdate($id = null, $type = null) {
		$classMDL = "frontend\modules\bbii\models\\".(is_string($type) ? 'Bbii'.$type : 'Bbii'.ucfirst(\Yii::$app->request->get('type')));
		$id       = (is_numeric($id) ?: \Yii::$app->request->get('id'));
		$model    = $classMDL::findOne($id);



        if ($model->load(\Yii::$app->request->post())) {

        	$success = ($model->validate() && $model->save() ? true : false);

			\Yii::$app->getSession()->setFlash(
				($success?'success':'warning'),
				Yii::t('BbiiModule.bbii', 'Change(s) '.(!$success?'NOT':NULL).'saved.')
			);
            
			return \Yii::$app->response->redirect( \Yii::$app->request->referrer );
        } else {
            return $this->render('update/'.strtolower(\Yii::$app->request->get('type')), [
                'model' => $model,
            ]);
        }

        return false;
    }

    /**
     * [actionCreatemembergroup description]
     * 
     * @return [type] [description]
     */
    public function actionCreatemembergroup() {
        $model = new BbiiMembergroup();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {

        	return \Yii::$app->response->redirect(['forum/setting/group']);
        } else {
            return $this->render('update/membergroup', [
                'model' => $model,
            ]);
        }
    }

    /**
     * [actionUpdatemembergroup description]
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function actionUpdatemembergroup($id = null) {
    	$id    = (is_numeric($id) ? $id : \Yii::$app->request->get('id'));
        $model = BbiiMembergroup::find()->where(['id' => $id])->one();

        // set data
        if ($model->load(\Yii::$app->request->post())) {

        	// validate and save
        	if ($model->validate() && $model->save()) {
				\Yii::$app->getSession()->addFlash('success', Yii::t('BbiiModule.bbii', 'Change saved.'));
				return \Yii::$app->response->redirect('group');
			// error when saving
        	}
            
        } else {
            return $this->render('update/membergroup', [
                'model' => $model,
            ]);
        }
    }

    /**
     * [actionCreatemembergroup description]
     * @return [type] [description]
     */
    public function actionCreatespider() {
        $model = new BbiiSpider();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {

        	return \Yii::$app->response->redirect(['forum/setting/spider']);
        } else {
            return $this->render('update/spider', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdatespider($id = null) {
    	$id    = (is_numeric($id) ? $id : \Yii::$app->request->get('id'));
        $model = BbiiSpider::find()->where(['id' => $id])->one();

        // set data
        if ($model->load(\Yii::$app->request->post())) {

        	// validate and save
        	if ($model->validate() && $model->save()) {
				\Yii::$app->getSession()->addFlash('success', Yii::t('BbiiModule.bbii', 'Change saved.'));
				return \Yii::$app->response->redirect('spider');
			// error when saving
        	}
            
        } else {

            return $this->render('update/spider', [
                'model' => $model,
            ]);
        }
    }
}