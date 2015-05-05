<?php
/* @var $this ModeratorController */
/* @var $model BbiiPost */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Approval'),
);

$approvals = BbiiPost::model()->unapproved()->count();
$reports = BbiiMessage::model()->report()->count();

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum'), 'url'=>array('forum/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Members'), 'url'=>array('member/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url'=>array('moderator/approval'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url'=>array('moderator/report'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Posts'), 'url'=>array('moderator/admin'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Blocked IP'), 'url'=>array('moderator/ipadmin'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Send mail'), 'url'=>array('moderator/sendmail'), 'visible'=>$this->isModerator()),
);
?>
<div id="bbii-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<?php 
	$dataProvider = $model->search();
	$dataProvider->setPagination(array('pageSize'=>10));
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'approval-grid',
		'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'name'=>'user_id',
				'value'=>'$data->poster->member_name'
			),
			'subject',
			'ip',
			array(
				'name' => 'create_time',
				'value' => 'DateTimeCalculation::long($data->create_time)',
			),
			array(
				'class'=>'CButtonColumn',
				'template'=>'{view}{approve}{delete}',
				'buttons' => array(
					'view' => array(
						'url'=>'$data->id',
						'imageUrl'=>$this->module->getRegisteredImage('view.png'),
						'click'=>'js:function() { viewPost($(this).attr("href"), "' . $this->createAbsoluteUrl('moderator/view') .'");return false; }',
					),
					'approve' => array(
						'url'=>'array("approve", "id"=>$data->id)',
						'label'=>Yii::t('BbiiModule.bbii','Approve'),
						'imageUrl'=>$this->module->getRegisteredImage('approve.png'),
						'options'=>array('style'=>'margin-left:5px;'),
					),
					'delete' => array(
						'imageUrl'=>$this->module->getRegisteredImage('delete.png'),
						'options'=>array('style'=>'margin-left:5px;'),
					),
				)
			),
		),
	)); ?>
	
	<div id="bbii-message"></div>

</div>