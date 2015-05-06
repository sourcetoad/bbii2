<?php

namespace frontend\modules\bbii;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	public $css 		= ['css/base/forum.css',];
	public $js 			= ['js/bbii.js'];
	public $sourcePath 	= '@app/modules/bbii/assets/';

	public $depends = [
		'yii\web\YiiAsset', 
	    'yii\bootstrap\BootstrapAsset',
	];
} 