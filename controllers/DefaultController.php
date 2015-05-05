<?php

namespace frontend\modules\bbii\controllers;

use Yii;
use yii\web\Controller;

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;


class DefaultController extends Controller
{
	public function actionIndex()
	{
        return $this->render('index', [
			'BbiiPost'    => new BbiiPost,
			'BbiiMessage' => new BbiiMessage,
        ]);
	}
}