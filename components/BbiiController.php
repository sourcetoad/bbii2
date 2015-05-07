<?php

namespace frontend\modules\bbii\components;

use yii\base\Controller;

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BbiiController extends Controller
{
	/**
	 * @deprecated 2.0
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='/layouts/column1';

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $bbii_breadcrumbs=array();
	
	public function getPageTitle() {
		$pageTitle = $this->module->forumTitle;
		if($this->getId() == 'forum') {
			switch ($this->getAction()->getId()) {
				case 'createTopic':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Create new topic');
					break;
				case 'forum':
					$pageTitle .= ' - ' . @BbiiForum::model()->findByPk($_GET['id'])->name;
					break;
				case 'topic':
					$pageTitle .= ' - ' . @BbiiTopic::model()->findByPk($_GET['id'])->title;
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
		} elseif($this->getId() == 'member') {
			switch ($this->getAction()->getId()) {
				case 'index':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Members');
					break;
				case 'view':
					$pageTitle .= ' - ' . @BbiiMember::model()->findByPk($_GET['id'])->member_name;
					break;
				case 'update':
					$pageTitle .= ' - ' . Yii::t('BbiiModule.bbii','Update');
					break;
			}
		} elseif($this->getId() == 'message') {
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
		$userId = Yii::$app->user->id;
		if($userId === null) {
			return false;		// not authenticated
		} else {
			if($this->module->adminId && $this->module->adminId == $userId) {
				return true;	// by module parameter assigned admin
			}
			if(Yii::$app->authManager && Yii::$app->user->checkAccess('admin')) {
				return true;	// rbac role "admin"
			}
		}
		return false;
	}
	
	/**
	 * Determine whether user has administrator authorization rights
	 * @return boolean
	 */
	public function isModerator() {
		$userId = Yii::$app->user->id;
		if($userId === null) {
			return false;		// not authenticated
		} else {
			if($this->isAdmin()) {
				return true;
			}
			if(Yii::$app->authManager && Yii::$app->user->checkAccess('moderator')) {
				return true;	// rbac role "moderator"
			}
			if(BbiiMember::model()->cache(900)->moderator()->exists("id = $userId")) {
				return true;	// member table moderator value set
			}
		}
		return false;
	}
}