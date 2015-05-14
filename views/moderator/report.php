<?php

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiMessage */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Reports'),
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
	$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'message-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'columns' => array(
			array(
				'name' => 'sendfrom',
				'value' => '$data->sender->member_name',
				'filter' => false,
			),
			'subject',
			array(
				'name' => 'content',
				'type' => 'html',
			),
			'create_time',
			array(
				'class' => 'CButtonColumn',
				'template' => '{view}{reply}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => 'array("forum/topic", "id" => $data->forumPost->topic_id, "nav" => $data->post_id)',
						'label' => Yii::t('BbiiModule.bbii','Go to post'),
						'imageUrl' => $assets->baseUrl.'goto.png'),
					),
					'reply' => array(
						'url' => 'array("message/reply", "id" => $data->id)',
						'label' => Yii::t('BbiiModule.bbii','Reply'),
						'imageUrl' => $assets->baseUrl.'reply.png'),
						'options' => array('style' => 'margin-left:5px;'),
					),
					'delete' => array(
						'url' => 'array("message/delete", "id" => $data->id)',
						'imageUrl' => $assets->baseUrl.'delete.png'),
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			),
		),
	)); ?>
</div>