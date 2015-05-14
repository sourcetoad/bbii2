<?php

namespace frontend\modules\bbii;

use Yii;

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
	public $userClass         = 'frontend\modules\user\Module'; // change this to your user module
	public $userIdColumn      = 'id';
	public $userMailColumn    = false;
	public $userNameColumn    = 'username';
	public $version           = '2.0.1';

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
		if($this->_assetsUrl == null) {
            $this->_assetsUrl = Yii::$app->assetManager->publish(Yii::getPathOfAlias($this->id.'.assets')
				// Comment the line below out in production.
				,false,-1,true
			);
		}
        return $this->_assetsUrl;
    }
	
	/**
	 * Register the CSS and JS files for the module
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
	 * @param string filename of the image
	 * @return string source URL of image
	 */
	public function getRegisteredImage($filename) {
		return $this->getAssetsUrl() .'/images/'. $filename;
    }

	
	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action)) {
			// this method is called before any module controller action is performed
			// you may place customized code here
			
			// register last visit by member
			if(Yii::$app->user->id) {
				$model = BbiiMember::find()->findByPk(Yii::$app->user->id);
				if($model !== null) {
					$model->last_visit 	 =  date('Y-m-d H:i:s');
					$model->save();
				} else {
					$criteria = new CDbCriteria;
					$criteria->condition = $this->userIdColumn . ' = :id';
					$criteria->params = array(':id' => Yii::$app->user->id);
					$class = new $this->userClass;
					$user = $class::find()->find($criteria);
					$username = $user->getAttribute($this->userNameColumn);
					$model = new BbiiMember;
					$model->id 			 =  Yii::$app->user->id;
					$model->member_name = $username;
					$model->first_visit = date('Y-m-d H:i:s');
					$model->last_visit 	 =  date('Y-m-d H:i:s');
					$model->save();
				}
			}
			// register visit by webspider
			if(isset($_SERVER['HTTP_USER_AGENT'])) {
				$spider = BbiiSpider::find()->findByAttributes(array('user_agent' => $_SERVER['HTTP_USER_AGENT']));
			} else {
				$spider = null;
			}
			if($spider !== null) {
				$spider->setScenario('visit');
				$spider->hits++;
				$spider->last_visit = null;
				$spider->save();
			} else {
				// register visit by guest (when not a webspider)
				$model = BbiiSession::find()->findByPk(Yii::$app->session->sessionID);
				if($model === null) {
					$model = new BbiiSession;
					$model->id = Yii::$app->session->sessionID;
				}
				$model->save();
			}
			// delete older session entries
			$criteria = new CDbCriteria;
			$criteria->condition = 'last_visit < "' . date('Y-m-d H:i:s', (time() - 24*3600)). '"';
			BbiiSession::find()->deleteAll($criteria);
			return true;
		}
		else
			return false;
	}
}
