<?php

namespace frontend\modules\bbii\components;

use frontend\modules\bbii\models\BbiiMember;

use Yii;
use yii\base\Controller;
use yii\data\ArrayDataProvider;
use yii\web\Response;

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BbiiController extends Controller
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout = '//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	//	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $bbii_breadcrumbs = array();
	
	public function getPageTitle() {
		$pageTitle = $this->context->module->forumTitle;
		if ($this->getId() == 'forum') {
			switch ($this->getAction()->getId()) {
				case 'createTopic':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Create new topic');
					break;
				case 'forum':
					$pageTitle .= ' - ' . @BbiiForum::find(Yii::$app->request->get()['id'])->name;
					break;
				case 'topic':
					$pageTitle .= ' - ' . @BbiiTopic::find(Yii::$app->request->get()['id'])->title;
					break;
				case 'quote':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Quote');
					break;
				case 'reply':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Reply');
					break;
				case 'update':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Update');
					break;
			}
		} elseif ($this->getId() == 'member') {
			switch ($this->getAction()->getId()) {
				case 'index':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Members');
					break;
				case 'view':
					$pageTitle .= ' - ' . @BbiiMember::find(Yii::$app->request->get()['id'])->member_name;
					break;
				case 'update':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Update');
					break;
			}
		} elseif ($this->getId() == 'message') {
			switch ($this->getAction()->getId()) {
				case 'inbox':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Inbox');
					break;
				case 'outbox':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Outbox');
					break;
				case 'create':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','New message');
					break;
			}
		} else {
			$pageTitle .= ' - ' . ucfirst($this->getAction()->getId());
		}
		return $pageTitle;
	}
	
	/**
	 * Determine whether user has administrator authorization rights
	 * @return boolean
	 */
	public function isAdmin() {

		if ($this->module->adminId && $this->module->adminId ===  Yii::$app->user->id) {
			return true;	// by module parameter assigned admin
		}

		if (Yii::$app->authManager && Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)['user']->type  == 3
		) {
			return true;	// rbac role "admin"
		}

		return false;
	}
	
	/**
	 * Determine whether user has administrator authorization rights
	 * @return boolean
	 */
	public function isModerator() {

		// user not logged into web-app
		if (!Yii::$app->user->id) { return false; }
		
		// rbac role "moderator"
		if (Yii::$app->authManager && Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)['user']->type  == 2 ) {
			return true;	
		}

		// @todo turn caching back on - DJE : 2015-06-03
		/* if (BbiiMember::find()->cache(900)->moderator()->exists("id = $userId")) {
			return true;	// member table moderator value set
		} */

		// if use is admin, they are admin
		if ($this->isAdmin()) { return true; }

		return false;
	}
}