<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this SettingController */
/* @var $model BbiiMembergroup */
/* @var $form ActiveForm */
?>

<div class = "form">

	<?php $form = ActiveForm::begin([
		'enableAjaxValidation' => true,
		'id'                   => 'edit-forum-form',
	]); ?>

		<?php //<p class = "note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class = "required">*</span> are required.'); ? ></p> ?>

		<?php //echo $form->errorSummary($model); ?>

		<div class = "row">
			<?php //echo $form->labelEx($model,'name'); ?>
			<?php echo $form->field($model,'name')->textInput(array('size' => 40)); ?>
			<?php //echo $form->error($model,'name'); ?>
		</div>

		<div class = "row">
			<?php //echo $form->labelEx($model,'description'); ?>
			<?php echo $form->field($model,'description')->textInput(array('size' => 40)); ?>
			<?php //echo $form->error($model,'description'); ?>
		</div>

		<div class = "row">
			<?php //echo $form->labelEx($model,'min_posts'); ?>
			<?php echo $form->field($model,'min_posts')->textInput(array('size' => 10)); ?>
			<?php //echo $form->error($model,'min_posts'); ?>
		</div>

		<div class = "row">
			<?php //echo $form->labelEx($model,'color'); ?>
			<?php echo $form->field($model, 'color')->textInput(array('id' => 'colorpickerField', 'style' => 'width:70px;', 'onchange' => 'BBiiSetting.ChangeColor(this)')); ?>
			<?php //echo Html::field('colorpickerColor', '', array('style' => 'width:40px;', 'readonly' => true)); ?>
			<?php //echo $form->error($model,'color'); ?>
		</div>

		<div class = "row">
			<?php //echo $form->labelEx($model,'image'); ?>
			<?php echo $form->field($model,'image')->textInput(array('size' => 40)); ?>
			<?php //echo $form->error($model,'image'); ?>
		</div>

		<div class = "row">
			<?php echo $form->field($model,'id')->hiddenInput()->label(false); ?>
		</div>
	
	<?php ActiveForm::end(); ?>

</div><!-- form -->