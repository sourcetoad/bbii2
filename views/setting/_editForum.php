<?php
/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edit-forum-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
		'validateOnChange'=>false,
	)
)); ?>

	<p class="note"><?= Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?= $form->errorSummary($model); ?>

	<div class="row">
		<?= $form->labelEx($model,'name'); ?>
		<?= $form->textField($model,'name',array('size'=>40)); ?>
		<?= $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'subtitle'); ?>
		<?= $form->textField($model,'subtitle',array('size'=>80)); ?>
		<?= $form->error($model,'subtitle'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'cat_id', array('id'=>'label_cat_id')); ?>
			<?= $form->dropDownList($model,'cat_id',Html::listData(BbiiForum::model()->categories()->findAll(), 'id', 'name'), array('empty'=>'')); ?>
		<?= $form->error($model,'cat_id'); ?>
	</div>
	
	<div class="row">
		<?= $form->labelEx($model,'public', array('id'=>'label_public')); ?>
		<?= $form->dropDownList($model,'public',array('0'=>Yii::t('BbiiModule.bbii', 'No'),'1'=>Yii::t('BbiiModule.bbii', 'Yes'))); ?>
		<?= $form->error($model,'public'); ?>
	</div>
	
	<div class="row">
		<?= $form->labelEx($model,'locked', array('id'=>'label_locked')); ?>
		<?= $form->dropDownList($model,'locked',array('0'=>Yii::t('BbiiModule.bbii', 'No'),'1'=>Yii::t('BbiiModule.bbii', 'Yes'))); ?>
		<?= $form->error($model,'locked'); ?>
	</div>
	
	<div class="row">
		<?= $form->labelEx($model,'moderated', array('id'=>'label_moderated')); ?>
		<?= $form->dropDownList($model,'moderated',array('0'=>Yii::t('BbiiModule.bbii', 'No'),'1'=>Yii::t('BbiiModule.bbii', 'Yes'))); ?>
		<?= $form->error($model,'moderated'); ?>
	</div>
	
	<div class="row">
		<?= $form->labelEx($model,'membergroup_id', array('id'=>'label_membergroup')); ?>
		<?= $form->dropDownList($model,'membergroup_id',Html::listData(BbiiMembergroup::model()->specific()->findAll(), 'id', 'name'), array('empty'=>'')); ?>
		<?= $form->error($model,'membergroup_id'); ?>
	</div>
	
	<div class="row">
		<?= $form->labelEx($model,'poll', array('id'=>'label_poll')); ?>
		<?= $form->dropDownList($model,'poll',array('0'=>Yii::t('BbiiModule.bbii', 'No polls'),'1'=>Yii::t('BbiiModule.bbii', 'Moderator polls'),'2'=>Yii::t('BbiiModule.bbii', 'User polls'))); ?>
		<?= $form->error($model,'poll'); ?>
	</div>
	
	<div class="row">
		<?= $form->hiddenField($model,'id'); ?>
		<?= $form->hiddenField($model,'type'); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->