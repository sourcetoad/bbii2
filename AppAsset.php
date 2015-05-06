<?php

namespace frontend\modules\bbii;

use yii\web\AssetBundle;
use yii\web\AssetManager;

class AppAsset extends AssetBundle
{
	public $sourcePath = '@app/modules/bbii/assets/';

	public $css     = ['@app/modules/assets/css/base/bbii.css'];
	public $depends = ['yii\web\YiiAsset', 'yii\bootstrap\BootstrapAsset'];
	public $js      = ['@app/modules/bbii/assets/js/bbii.js'];



	public function publish($param_data)
	{
		$am = new AssetManager();
		$am->publish('@app/modules/bbii/assets/css/base/');
		$am->publish('@app/modules/bbii/assets/css/base/images/');
		$am->publish('@app/modules/bbii/assets/images/');
	}
} 