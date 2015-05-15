<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\grid\GridView;
use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this IpaddressController */
/* @var $model Ipaddress */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Blocked IP'),
);

$approvals = BbiiPost::find()->unapproved()->count();
$reports = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 								'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 								'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 	'url' => array('moderator/approval'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 	' (' . $reports . ')', 		'url' => array('moderator/report'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 								'url' => array('moderator/admin'), 		'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 							'url' => array('moderator/ipadmin'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 							'url' => array('moderator/sendmail'), 	'visible' => $this->context->isModerator()),
);
?>

<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>

	<?php // @depricated 2.1.5 Kept for referance
	/*$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'ipaddress-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'columns' => array(
			'ip',
			'address',
			'count',
			'create_time',
			'update_time',
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
				'buttons' => array(
					'delete' => array(
						'url' => 'array("moderator/ipDelete", "id" => $data->id)',
						'imageUrl' => $assets->baseUrl.'/images/delete.png',
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			),
		),
	));*/ ?>

	<?php
	echo GridView::widget(array(
		'columns'      => array(
			'ip',
			'address',
			'count',
			'create_time',
			'update_time',
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete}',
				'buttons' => array(
					'delete' => array(
						'url' => 'array("moderator/ipDelete", "id" => $data->id)',
						'imageUrl' => $assets->baseUrl.'/images/delete.png',
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			)),
		'dataProvider' => $model->search(),
		'id'           => 'member-grid',
	)); ?>
</div>