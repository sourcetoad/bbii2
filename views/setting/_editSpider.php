<?php
/* @var $this SettingController */
/* @var $model BbiiSpider */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edit-spider-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>25)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_agent'); ?>
		<?php echo $form->textField($model,'user_agent',array('size'=>70)); ?>
		<?php echo $form->error($model,'user_agent'); ?>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'id'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->