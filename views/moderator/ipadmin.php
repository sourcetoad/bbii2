<?php
/* @var $this IpaddressController */
/* @var $model Ipaddress */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Blocked IP'),
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

	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'ipaddress-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>array(
			'ip',
			'address',
			'count',
			/*
			'create_time',
			*/
			'update_time',
			array(
				'class'=>'CButtonColumn',
				'template'=>'{delete}',
				'buttons' => array(
					'delete' => array(
						'url'=>'array("moderator/ipDelete", "id"=>$data->id)',
						'imageUrl'=>$this->module->getRegisteredImage('delete.png'),
						'options'=>array('style'=>'margin-left:5px;'),
					),
				)
			),
		),
	)); ?>
</div>