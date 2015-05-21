<?php

use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiMembergroup;

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $form ActiveForm */
?>

<div class = "form">

	<?php $form = ActiveForm::begin([
		'enableAjaxValidation' => true,
		'id'                   => 'edit-forum-form',
	]); ?>

	<?php //<p class = "note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class = "required">*</span> are required.'); ? ></p> ?>

	<?php echo $form->errorSummary($model); ?>

	<div class = "row">
		<?php //echo $form->labelEx($model,'name'); ?>
		<?php echo $form->field($model,'name')->textInput(array('value' => $model->getAttribute('name'), 'size' => 40)); ?>
		<?php //echo $form->error($model,'name'); ?>
	</div>

	<div class = "row">
		<?php //echo $form->labelEx($model,'subtitle'); ?>
		<?php echo $form->field($model,'subtitle')->textInput(array('size' => 80)); ?>
		<?php //echo $form->error($model,'subtitle'); ?>
	</div>

	<div class="row">
		<div class="form-group field-bbiiforum-cateogry">
			<label class="control-label" for="name">Categories</label>
			<?php //echo $form->labelEx($model,'cat_id', array('id' => 'label_cat_id')); ?>
				<?php echo Html::dropDownList($model,'cat_id',ArrayHelper::map(BbiiForum::find()->categories()->all(), 'id', 'name'), array('empty' => '', 'class' => 'form-control')); ?>
			<?php //echo $form->error($model,'cat_id'); ?>
		</div>
	</div>


	<div class="row">
		<div class="form-group field-bbiiforum-public">
			<label class="control-label" for="name">Public</label>
			<?php //echo $form->labelEx($model,'public', array('id' => 'label_public')); ?>
			<?php echo Html::dropDownList($model,'public',array('0' => Yii::t('BbiiModule.bbii', 'No'),'1' => Yii::t('BbiiModule.bbii', 'Yes')), array('class' => 'form-control')); ?>
			<?php //echo $form->error($model,'public'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group field-bbiiforum-locked">
			<label class="control-label" for="name">Locked</label>
			<?php //echo $form->labelEx($model,'locked', array('id' => 'label_locked')); ?>
			<?php echo Html::dropDownList($model,'locked',array('0' => Yii::t('BbiiModule.bbii', 'No'),'1' => Yii::t('BbiiModule.bbii', 'Yes')), array('class' => 'form-control')); ?>
			<?php //echo $form->error($model,'locked'); ?>
		</div>
	</div>
	
		<div class="row">
		<div class="form-group field-bbiiforum-moderated">
			<label class="control-label" for="name">Moderated</label>
			<?php //echo $form->labelEx($model,'moderated', array('id' => 'label_moderated')); ?>
			<?php echo Html::dropDownList($model,'moderated',array('0' => Yii::t('BbiiModule.bbii', 'No'),'1' => Yii::t('BbiiModule.bbii', 'Yes')), array('class' => 'form-control')); ?>
			<?php //echo $form->error($model,'moderated'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group field-bbiiforum-membergroup">
			<label class="control-label" for="name">Member Group</label>
			<?php //echo $form->labelEx($model,'membergroup_id', array('id' => 'label_membergroup')); ?>
			<?php echo Html::dropDownList($model,'membergroup_id',ArrayHelper::map(BbiiMembergroup::find()->specific()->findAll(), 'id', 'name'), array('empty' => '', 'class' => 'form-control')); ?>
			<?php //echo $form->error($model,'membergroup_id'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group field-bbiiforum-poll">
			<label class="control-label" for="name">Poll</label>
			<?php //echo $form->labelEx($model,'poll', array('id' => 'label_poll')); ?>
			<?php echo Html::dropDownList($model,'poll',array('0' => Yii::t('BbiiModule.bbii', 'No polls'),'1' => Yii::t('BbiiModule.bbii', 'Moderator polls'),'2' => Yii::t('BbiiModule.bbii', 'User polls')), array('class' => 'form-control')); ?>
			<?php //echo $form->error($model,'poll'); ?>
		</div>
	</div>
	
	<div class = "row">
		<?php echo $form->field($model,'id')->hiddenInput()->label(false); ?>
		<?php echo $form->field($model,'type')->hiddenInput()->label(false); ?>
	</div>

	<?php ActiveForm::end(); ?>

</div><!-- form -->
