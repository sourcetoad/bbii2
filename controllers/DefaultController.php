<?php

namespace frontend\modules\bbii\controllers;

use Yii;
use yii\web\User;

use frontend\modules\bbii\components\BbiiController;
use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiPost;


class DefaultController extends BbiiController
{
	/**
	 * @author  David J Eddy me@davidjeddy.com
	 * @version  2.0.1
	 * @since na
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$approvals = BbiiPost::find()->All();   //->model()->unapproved()->count();
		$reports   = BbiiMessage::find()->All();//->model()->report()->count();

		// get user messages
		$messages = BbiiMessage::find()
			->where(['read_indicator' => 0, 'sendto'=> Yii::$app->user->id,])
			->count();

        return $this->render('index', [
			'approvals' => $approvals,
			'messages'  => $messages,
			'reports'   => $reports,
        ]);
	}
}