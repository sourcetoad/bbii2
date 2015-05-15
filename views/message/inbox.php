<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\UrlManager;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $count Array */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Inbox'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Inbox') .' ('. $count['inbox'] .')', 'url' => array('message/inbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'Outbox') .' ('. $count['outbox'] .')', 'url' => array('message/outbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'New message'), 'url' => array('message/create'))
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<div class = "progress"><div class = "progressbar" style = "width:<?php echo ($count['inbox'] < 100)?$count['inbox']:100; ?>%"> </div></div>

	<?php // @depricated 2.1.5 Kept for referance
	/*$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'inbox-grid',
		'dataProvider' => $model->search(),
		'rowCssClassExpression' => '($data->read_indicator)?"":"unread"',
		'columns' => array(
			array(
				'name' => 'sendfrom',
				'value' => '$data->sender->member_name'
			),
			'subject',
			array(
				'name' => 'create_time',
				'value' => 'DateTimeCalculation::long($data->create_time)',
			),
			array(
				'name' => 'type',
				'value' => '($data->type)?Yii::t("bbii", "notification"):Yii::t("bbii", "message")',
			),
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{reply}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => '$data->id',
						'imageUrl' => $assets->baseUrl.'view.png',
						'click' => 'js:function() { viewMessage($(this).attr("href"), "' . Yii::$app->urlManager->createAbsoluteUrl('message/view') .'");return false; }',
					),
					'reply' => array(
						'url' => 'array("reply", "id" => $data->id)',
						'label' => Yii::t('BbiiModule.bbii','Reply'),
						'imageUrl' => $assets->baseUrl.'reply.png',
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

	<?php echo GridView::widget(array(
		'columns'      => array(
			array(
				'header' => 'sendfrom',
				'value'  => '$data->sender->member_name'
			),
			'subject',
			array(
				'header' => 'create_time',
				'value'  => 'DateTimeCalculation::long($data->create_time)',
			),
			array(
				'header' => 'type',
				'value'  => '($data->type)?Yii::t("bbii", "notification"):Yii::t("bbii", "message")',
			),
			array(
				'buttons'  => array(
					'view' => array(
						'click'    => 'js:function() { viewMessage($(this).attr("href"), "' . Yii::$app->urlManager->createAbsoluteUrl('message/view') .'");return false; }',
						'imageUrl' => $assets->baseUrl.'view.png',
						'url'      => '$data->id',
					),
					'reply' => array(
						'imageUrl' => $assets->baseUrl.'reply.png',
						'label'    => Yii::t('BbiiModule.bbii','Reply'),
						'options'  => array('style' => 'margin-left:5px;'),
						'url'      => 'array("reply", "id" => $data->id)',
					),
					'delete' => array(
						'imageUrl' => $assets->baseUrl.'/images/delete.png',
						'options'  => array('style' => 'margin-left:5px;'),
					),),
				'class'    => 'yii\grid\ActionColumn',
				'template' => '{view}{reply}{delete}',
			)),
		'dataProvider'          => $model->search(),
		'id'                    => 'inbox-grid',
		// @todo Figure out the Yii2 version of this logic - DJE : 2015-05-15
		//'rowCssClassExpression' => '($data->read_indicator)?"":"unread"',
	)); ?>

	<div id = "bbii-message"></div>

</div>