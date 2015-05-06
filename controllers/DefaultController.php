<?php

namespace frontend\modules\bbii\controllers;

use Yii;
use yii\web\Controller;
use yii\web\User;

use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiPost;


class DefaultController extends Controller
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

		// get items
		$item = array(
			array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 									'url' => array('forum/index')),
			array('label' => Yii::t('BbiiModule.bbii', 'Members'), 									'url' => array('member/index')),
			array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . count($approvals) . ')', 'url' => array('moderator/approval')),
			array('label' => Yii::t('BbiiModule.bbii', 'Reports').  ' (' . count($reports) . ')', 	'url' => array('moderator/report'),		'visible' => (Yii::$app->session->get('user.status') > 60 ? true : false)),
		);

		// get user messages
		$messages = BbiiMessage::find()->where([
			'read_indicator' => 0,
			'sendto'         => Yii::$app->user->id,
		])->count();

        return $this->render('index', [
			'BbiiMessage'    => new BbiiMessage,
			'BbiiPost'       => new BbiiPost,
			//'dataProvider' => $dataProvider,
			'messages'       => $messages,
			'item'			 => $item,
        ]);
	}
}