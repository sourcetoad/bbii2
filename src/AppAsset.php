<?php

namespace sourcetoad\bbii2;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $css        = ['css/base/forum.css',];
    public $js         = ['js/bbii.js', 'js/bbiiSetting.js'];
    public $sourcePath = '@vendor/sourcetoad/bbii2/src/assets/';

    public $depends = [
        'yii\web\YiiAsset', 
        'yii\bootstrap\BootstrapAsset',
    ];
} 