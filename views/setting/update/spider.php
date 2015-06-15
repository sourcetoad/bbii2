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

		<div>
			<?php echo $form->field($model,'name')->textInput(array('size' => 25)); ?>
		</div>

		<div>
			<?php echo $form->field($model,'user_agent')->textInput(array('size' => 70)); ?>
		</div>

		<div>
			<?php echo $form->field($model,'hits')->textInput(array('size' => 11)); ?>
		</div>

		<div>
			<?php echo $form->field($model,'id')->hiddenInput()->label(false); ?>
		</div>

		<div class = "button">
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Save'), array('class' => 'btn btn-success btn-lg')); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div><!-- form -->
