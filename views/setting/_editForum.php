<?php

use yii\bootstrap\ActiveForm;

/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $form CActiveForm */
?>

<div class = "form">

<?php $form = ActiveForm::begin([
	'enableAjaxValidation' => true,
	'id'                   => 'edit-forum-form',
	'validateOnChange'     => false,
	'validateOnSubmit'     => true,
]); ?>
<?php // @todo Iterate on forms - DJE : 2015-05-15
/*
	<p class = "note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class = "required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class = "row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size' => 40)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class = "row">
		<?php echo $form->labelEx($model,'subtitle'); ?>
		<?php echo $form->textField($model,'subtitle',array('size' => 80)); ?>
		<?php echo $form->error($model,'subtitle'); ?>
	</div>

	<div class = "row">
		<?php echo $form->labelEx($model,'cat_id', array('id' => 'label_cat_id')); ?>
			<?php echo $form->dropDownList($model,'cat_id',ArrayHelper::map(BbiiForum::find()->categories()->findAll(), 'id', 'name'), array('empty' => '')); ?>
		<?php echo $form->error($model,'cat_id'); ?>
	</div>
	
	<div class = "row">
		<?php echo $form->labelEx($model,'public', array('id' => 'label_public')); ?>
		<?php echo $form->dropDownList($model,'public',array('0' => Yii::t('BbiiModule.bbii', 'No'),'1' => Yii::t('BbiiModule.bbii', 'Yes'))); ?>
		<?php echo $form->error($model,'public'); ?>
	</div>
	
	<div class = "row">
		<?php echo $form->labelEx($model,'locked', array('id' => 'label_locked')); ?>
		<?php echo $form->dropDownList($model,'locked',array('0' => Yii::t('BbiiModule.bbii', 'No'),'1' => Yii::t('BbiiModule.bbii', 'Yes'))); ?>
		<?php echo $form->error($model,'locked'); ?>
	</div>
	
	<div class = "row">
		<?php echo $form->labelEx($model,'moderated', array('id' => 'label_moderated')); ?>
		<?php echo $form->dropDownList($model,'moderated',array('0' => Yii::t('BbiiModule.bbii', 'No'),'1' => Yii::t('BbiiModule.bbii', 'Yes'))); ?>
		<?php echo $form->error($model,'moderated'); ?>
	</div>
	
	<div class = "row">
		<?php echo $form->labelEx($model,'membergroup_id', array('id' => 'label_membergroup')); ?>
		<?php echo $form->dropDownList($model,'membergroup_id',ArrayHelper::map(BbiiMembergroup::find()->specific()->findAll(), 'id', 'name'), array('empty' => '')); ?>
		<?php echo $form->error($model,'membergroup_id'); ?>
	</div>
	
	<div class = "row">
		<?php echo $form->labelEx($model,'poll', array('id' => 'label_poll')); ?>
		<?php echo $form->dropDownList($model,'poll',array('0' => Yii::t('BbiiModule.bbii', 'No polls'),'1' => Yii::t('BbiiModule.bbii', 'Moderator polls'),'2' => Yii::t('BbiiModule.bbii', 'User polls'))); ?>
		<?php echo $form->error($model,'poll'); ?>
	</div>
	
	<div class = "row">
		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->hiddenField($model,'type'); ?>
	</div>
*/ ?>	
<?php ActiveForm::end(); ?>

</div><!-- form -->