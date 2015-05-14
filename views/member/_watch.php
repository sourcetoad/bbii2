<?php 
/* @var $this MemberController */
/* @var $topicProvider CActiveDataProvider BbiiTopic*/

?>
<div class = "header2"><?php echo Yii::t('BbiiModule.bbii','Watching topics'); ?></div>
<div class = "form">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'id' => 'bbii-watch-form',
		'method' => 'get',
		'enableAjaxValidation' => false,
	)); ?>
	
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'watch-grid',
		'dataProvider' => $topicProvider,
		'template' => '{items}',
		'columns' => array(
			array(
				'header' => '',
				'value' => 'CHtml::checkbox("unwatch[$data->id]")',
				'type' => 'raw',
			),
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Topic'),
				'value' => 'CHtml::link($data->title, array("forum/topic", "id" => $data->id, "nav" => "last"))',
				'type' => 'raw',
			),
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Forum'),
				'value' => 'CHtml::link($data->forum->name, array("forum/forum", "id" => $data->forum_id))',
				'type' => 'raw',
			),
			array(
				'name' => 'num_replies',
				'htmlOptions' => array('class' => 'center'),
			),
			array(
				'name' => 'num_views',
				'htmlOptions' => array('class' => 'center'),
			),
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Started by'),
				'value' => '$data->starter->member_name',
				'type' => 'raw',
			),
		),
	)); ?>	
	
	<div class = "row buttons">
		<?php echo CHtml::submitButton(Yii::t('BbiiModule.bbii', 'Delete')); ?>
	</div>
	
	<?php $this->endWidget(); ?>
</div><!-- form -->