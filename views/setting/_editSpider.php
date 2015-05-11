<?php
/* @var $this SettingController */
/* @var $model BbiiSpider */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id' => 'edit-spider-form',
	'enableAjaxValidation' => true,
)); ?>

	<p class="note"><?= Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?= $form->errorSummary($model); ?>

	<div class="row">
		<?= $form->labelEx($model,'name'); ?>
		<?= $form->textField($model,'name',array('size' => 25)); ?>
		<?= $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'user_agent'); ?>
		<?= $form->textField($model,'user_agent',array('size' => 70)); ?>
		<?= $form->error($model,'user_agent'); ?>
	</div>

	<div class="row">
		<?= $form->hiddenField($model,'id'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->