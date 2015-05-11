<?php
/* @var $this SettingController */
/* @var $model BbiiMembergroup */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id' => 'edit-membergroup-form',
	'enableAjaxValidation' => true,
)); ?>

	<p class="note"><?= Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?= $form->errorSummary($model); ?>

	<div class="row">
		<?= $form->labelEx($model,'name'); ?>
		<?= $form->textField($model,'name',array('size' => 40)); ?>
		<?= $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'description'); ?>
		<?= $form->textField($model,'description',array('size' => 40)); ?>
		<?= $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'min_posts'); ?>
		<?= $form->textField($model,'min_posts',array('size' => 10)); ?>
		<?= $form->error($model,'min_posts'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'color'); ?>
		<?= $form->textField($model, 'color', array('id' => 'colorpickerField', 'style' => 'width:70px;', 'onchange' => 'BBiiSetting.ChangeColor(this)')); ?>
		<?= Html::textField('colorpickerColor', '', array('style' => 'width:40px;', 'readonly' => true)); ?>
		<?= $form->error($model,'color'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'image'); ?>
		<?= $form->textField($model,'image',array('size' => 40)); ?>
		<?= $form->error($model,'image'); ?>
	</div>

	<div class="row">
		<?= $form->hiddenField($model,'id'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->