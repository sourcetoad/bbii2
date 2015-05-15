<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\grid\GridView;
use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiPost */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Approval'),
);

$approvals = BbiiPost::find()->unapproved()->count();
$reports = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 							'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 							'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')','url' => array('moderator/approval'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 	' (' . $reports . ')', 	'url' => array('moderator/report'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 							'url' => array('moderator/admin'), 		'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 						'url' => array('moderator/ipadmin'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 						'url' => array('moderator/sendmail'), 	'visible' => $this->context->isModerator()),
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<?php // @depricated 2.1.5 Kept for referance
	/*$dataProvider = $model->search();
	$dataProvider->setPagination(array('pageSize' => 10));
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'approval-grid',
		'dataProvider' => $dataProvider,
		'columns' => array(
			array(
				'name' => 'user_id',
				'value' => '$data->poster->member_name'
			),
			'subject',
			'ip',
			array(
				'name' => 'create_time',
				'value' => 'DateTimeCalculation::long($data->create_time)',
			),
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{approve}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => '$data->id',
						'imageUrl' => $assets->baseUrl.'view.png',
						'click' => 'js:function() { viewPost($(this).attr("href"), "' . Yii::$app->urlManager->createAbsoluteUrl('moderator/view') .'");return false; }',
					),
					'approve' => array(
						'url' => 'array("approve", "id" => $data->id)',
						'label' => Yii::t('BbiiModule.bbii','Approve'),
						'imageUrl' => $assets->baseUrl.'/images/approve.png',
						'options' => array('style' => 'margin-left:5px;'),
					),
					'delete' => array(
						'imageUrl' => $assets->baseUrl.'/images/delete.png',
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			),
		),
	));*/ ?>

	<?php
	$dataProvider = $model->search();
	$dataProvider->setPagination(array('pageSize' => 10));
	echo GridView::widget(array(
		'columns' => array(
			array(
				'header' => 'Member Name',
				'value'  => '$data->poster->member_name'
			),
			'subject',
			'ip',
			array(
				'header' => 'Create Time',
				'value'  => 'DateTimeCalculation::long($data->create_time)',
			),
			array(
				'class'    => 'yii\grid\ActionColumn',
				'template' => '{view}{approve}{delete}',
				'buttons' => array(
					'view' => array(
						'click'    => 'js:function() { viewPost($(this).attr("href"), "' .'moderator/view' .'");return false; }',
						'imageUrl' => $assets->baseUrl.'view.png',
						'url'      => '$data->id',
					),
					'approve' => array(
						'imageUrl' => $assets->baseUrl.'/images/approve.png',
						'label'    => Yii::t('BbiiModule.bbii','Approve'),
						'options'  => array('style' => 'margin-left:5px;'),
						'url'      => 'array("approve", "id" => $data->id)',
					),
					'delete' => array(
						'imageUrl' => $assets->baseUrl.'/images/delete.png',
						'options'  => array('style' => 'margin-left:5px;'),
					),
				),
			),
		),
		'dataProvider' => $dataProvider,
		'id'           => 'member-grid',
	)); ?>

	<div id = "bbii-message"></div>

</div>