<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\grid\GridView;
use yii\helpers\Html;
use yii\i18n\Formatter;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiPost */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Approval'),
);
?>

<div id = "bbii-wrapper" class="well clearfix">
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
				'value' => '\Yii::$app->formatter->asDatetime($data->create_time)',
			),
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{approve}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => '$data->id',
						'imageUrl' => $assets->baseUrl.'view.png',
						'click' => 'js:function() { viewPost($(this).attr("href"), "' . \Yii::$app->urlManager->createAbsoluteUrl('moderator/view') .'");return false; }',
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
	echo GridView::widget(array(
		'columns'         => array(
			array(
				'header' => 'Member Name',
				'value'  => 'poster.member_name',
			),

			array(
				'header' => 'Forum Name',
				'value'  => 'forum.name',
			),

			'subject',
			'create_time:datetime',

			['class' => 'yii\grid\ActionColumn'],
		),
		'dataProvider'    => $dataProvider,
		'id'              => 'bbii-member-grid',
	)); ?>

	<div id = "bbii-message"></div>

</div>