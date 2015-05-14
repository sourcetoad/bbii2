<?php

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
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url' => array('moderator/approval'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url' => array('moderator/report'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 'url' => array('moderator/admin'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 'url' => array('moderator/ipadmin'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 'url' => array('moderator/sendmail'), 'visible' => $this->context->isModerator()),
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<?php 
	$dataProvider = $model->search();
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
				'class' => 'CButtonColumn',
				'template' => '{view}{approve}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => '$data->id',
						'imageUrl' => $asset->baseUrl.'view.png'),
						'click' => 'js:function() { viewPost($(this).attr("href"), "' . $this->createAbsoluteUrl('moderator/view') .'");return false; }',
					),
					'approve' => array(
						'url' => 'array("approve", "id" => $data->id)',
						'label' => Yii::t('BbiiModule.bbii','Approve'),
						'imageUrl' => $asset->baseUrl.'approve.png'),
						'options' => array('style' => 'margin-left:5px;'),
					),
					'delete' => array(
						'imageUrl' => $asset->baseUrl.'delete.png'),
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			),
		),
	)); ?>
	
	<div id = "bbii-message"></div>

</div>