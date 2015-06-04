<?php

namespace frontend\modules\bbii;

use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiSpider;
use frontend\modules\bbii\models\BbiiSession;

use yii;
use yii\db\BaseActiveRecord;
use yii\web\Application;
use yii\web\Session;

class BbiiModule extends \yii\base\Module
 {
	public $adminId           = false;		// must be overridden to assign admin rights to user id
	public $allowTopicSub     = false;
	public $avatarStorage     = '/avatar'; 	// directory in the webroot must exist and allow read/write access
	public $bbiiTheme         = 'base';
	public $dbName            = false;
	public $defaultRoute      = 'forum/index';
	public $editorContentsCss = array();
	public $editorSkin        = 'moono';
	public $editorToolbar     = array(
		array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
		array('Find','Replace','-','SelectAll'),
		array('Bold', 'Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
		'-',
		array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
		'/',
		array('Styles','Format','Font','FontSize'),
		array('TextColor','BGColor'),
		array('HorizontalRule','Smiley','SpecialChar','-','ShowBlocks'),
		array('Link', 'Unlink','Image','Iframe')
	);
	public $editorUIColor     = '';
	public $forumTitle        = 'Forums';
	public $juiTheme          = 'base';
	public $purifierOptions   = array(
		'HTML.SafeIframe'          => true,
		'URI.SafeIframeRegexp'     => '%^http://(www.youtube.com/embed/|player.vimeo.com/video/)%',
	);
	public $postsPerPage      = 20;
	public $topicsPerPage     = 20;
	public $userClass         = 'common\models\User'; // change this to your user module
	public $userIdColumn      = 'id';
	public $userMailColumn    = 'email';
	public $userNameColumn    = 'username';
	public $version           = '3.0.5';

	private $_assetsUrl;
	
	public function init() {
		$this->registerAssets();

		// @depricated 2.0.0 Use the parent applications error settings
		/*
		Yii::$app->setComponents(
			array(
		        'errorHandler' => [
		            'errorAction' => 'site/error'
		        ],
			)
		);
		*/
		
		// @todo no longer needed per Yii2
		/*
		// import the module-level models and components
		$this->setImport(array(
			$this->id.'.models.*',
			$this->id.'.components.*',
		));
		*/

        parent::init();
	}
	
    /**
     * @return string base URL that contains all published asset files of this module.
     */
    public function getAssetsUrl() {
		if ($this->_assetsUrl == null) {
            $this->_assetsUrl = Yii::$app->assetManager->publish(Yii::getPathOfAlias($this->id.'.assets')
				// Comment the line below out in production.
				,false,-1,true
			);
		}
        return $this->_assetsUrl;
    }
	
	/**
	 * Register the CSS and JS files for the module
	 *
	 * @deprecated 2.0.1
	 */
	public function registerAssets() {
		return true;
		/*
		Yii::$app->clientScript->registerCssFile($this->getAssetsUrl() . '/css/' . $this->bbiiTheme . '/forum.css');
		Yii::$app->getClientScript()->registerCoreScript('jquery.ui');
		Yii::$app->clientScript->registerScriptFile($this->getAssetsUrl() . '/js/bbii.js', CClientScript::POS_HEAD);
		*/
	}
	
	/**
	 * Retrieve url of image in the assets
	 *
	 * @deprecated 2.2.0
	 * @param string filename of the image
	 * @return string source URL of image
	 */
	public function getRegisteredImage($filename) {
		return true;
		//return $this->getAssetsUrl() .'/images/'. $filename;
    }

	/**
	 * this method is called before any module controller action is performed
	 * you may place customized code here
	 *
	 * @version  2.2.0
	 * @param  [type] $controller [description]
	 * @param  [type] $action     [description]
	 * @return [type]             [description]
	 */
	public function beforeAction($controller, $action)
	{
		if (parent::beforeAction($controller, $action)) {

			// register last visit by member
			if (Yii::$app->user->id) {
				//$model = BbiiMember::find(Yii::$app->user->id);
				$model = BbiiMember::find()->where(['id' => Yii::$app->user->id])->one();

				if ($model !== null) {
					$model->setAttribute('last_visit', date('Y-m-d H:i:s'));
					$model->save();
				} else {
					$userClass = new User;
					$user      = $userClass::find()->where([$this->userIdColumn => Yii::$app->user->id])->one();
					$username  = $user->getAttribute($this->userNameColumn);

					$model              = new BbiiMember;
					$model->setAttribute('first_visit', date('Y-m-d H:i:s'));
					$model->setAttribute('id', 			Yii::$app->user->id);
					$model->setAttribute('last_visit', 	date('Y-m-d H:i:s'));
					$model->setAttribute('member_name', $username);
					$model->save();
				}
			}

			// register visits
			if (isset($_SERVER['HTTP_USER_AGENT'])) {

				// web spider visit
				$spider = BbiiSpider::find()->where(['user_agent' => $_SERVER['HTTP_USER_AGENT']])->one();
				if ($spider !== null) {
					$spider->setScenario('visit');
					$spider->hits++;
					$spider->last_visit = null;
					if (!$spider->validate() || !$spider->save()) {
						echo '<pre>';
						print_r( $spider->getErrors() );
						echo '</pre>';
						exit;
					}
				// guest visit
				} else {
					$model = BbiiSession::find()->where(['id' => Yii::$app->session->getId()])->one();
					$model = $model ?: new BbiiSession();

					$model->id = Yii::$app->session->getId();

					if (!$model->validate() || !$model->save()) {
						echo '<pre>';
						print_r( $model->getErrors() );
						echo '</pre>';
						exit;
					}
				}
			}

			// delete older session entries
			BbiiSession::deleteAll('last_visit < \'' . date('Y-m-d H:i:s', (time() - 24*3600)).'\'');
			
			return true;
		}
		
		return false;
	}
}
