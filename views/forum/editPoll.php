<?php
/* @var $this ForumController */
/* @var $poll BbiiPoll */
/* @var $choices array */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edit-poll-form',
	'action'=>array('forum/updatePoll','id'=>$poll->id),
	'enableAjaxValidation'=>false,
)); ?><div class="row">
	<?php echo CHtml::activeLabel($poll,'question'); ?>
	<?php echo CHtml::activeTextField($poll,'question',array('size'=>100,'maxlength'=>255,'style'=>'width:99%;')); ?>
	<?php echo CHtml::error($poll,'question'); ?>
</div>
<?php echo CHtml::errorSummary($poll); ?>
<div class="row">
	<?php echo CHtml::label(Yii::t('BbiiModule.bbii','Choices'),false); ?>
	<?php foreach($choices as $key => $value): ?>
	<?php echo CHtml::textField('choice['.$key.']',$value,array('maxlength'=>80,'style'=>'width:99%;')); ?>
	<?php endforeach; ?>
</div>
<div class="row">
	<strong><?php echo Yii::t('BbiiModule.bbii','Allow revote'); ?>:</strong>
	<?php echo CHtml::activeCheckbox($poll,'allow_revote'); ?> &nbsp;
	<strong><?php echo Yii::t('BbiiModule.bbii','Allow multiple choices'); ?>:</strong>
	<?php echo CHtml::activeCheckbox($poll,'allow_multiple'); ?> &nbsp;
	<strong><?php echo Yii::t('BbiiModule.bbii','Poll expires'); ?>:</strong>
	<?php echo $form->hiddenField($poll,'expire_date'); ?>
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
		'name'=>'expiredate',
		'value'=>Yii::$app->dateFormatter->formatDateTime($poll->expire_date, 'short', null),
		'language'=>substr(Yii::$app->language, 0, 2),
		'theme'=>$this->module->juiTheme,
		'options'=>array(
			'altField'=>'#BbiiPoll_expire_date',
			'altFormat'=>'yy-mm-dd',
			'showAnim'=>'fold',
			'defaultDate'=>7,
			'minDate'=>1,
		),
		'htmlOptions'=>array(
			'style'=>'height:18px;width:75px;',
		),
	)); ?>
</div>
<div class="row button">
	<?php echo CHtml::submitButton(Yii::t('BbiiModule.bbii','Save')); ?>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->