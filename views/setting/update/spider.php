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
		'enableAjaxValidation' => false,
		'id'                   => 'edit-spider-form',
	]); ?>

		<?php echo $form->errorSummary($model); ?>

		<div class = "row">
			<?php echo $form->field($model,'name')->textInput(array('size' => 25)); ?>
		</div>

		<div class = "row">
			<?php echo $form->field($model,'user_agent')->textInput(array('size' => 70)); ?>
		</div>

		<div class = "row">
			<?php echo $form->field($model,'hits')->textInput(array('size' => 11)); ?>
		</div>

		<div class = "row">
			<?php echo $form->field($model,'id')->hiddenInput()->label(false); ?>
		</div>

		<div class = "row button">
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Save'), array('class' => 'btn btn-success')); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div><!-- form -->