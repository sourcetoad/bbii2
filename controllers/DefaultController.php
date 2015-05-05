<?php

namespace frontend\modules\bbii\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
}