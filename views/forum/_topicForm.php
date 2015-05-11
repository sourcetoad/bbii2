<?php
/* @var $this ForumController */
/* @var $model BbiiTopic */
/* @var $form CActiveForm */
?>

<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'update-topic-form',
		'enableAjaxValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'validateOnChange' => false,
		)
	)); ?>
		<?= $form->errorSummary($model); ?>
		
		<div class="row">
			<?= $form->labelEx($model,'forum_id'); ?>
			<?= $form->dropDownList($model,'forum_id',Html::listData(BbiiForum::find()->forum()->findAll(), 'id', 'name'), array('onchange' => 'refreshTopics(this, "' . $this->createAbsoluteUrl('moderator/refreshTopics') . '")')); ?>
			<?= $form->error($model,'forum_id'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'merge'); ?>
			<?= $form->dropDownList($model,'merge',array()); ?>
			<?= $form->error($model,'merge'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'title'); ?>
			<?= $form->textField($model,'title',array('size' => 100,'maxlength' => 255,'style' => 'width:99%;')); ?>
			<?= $form->error($model,'title'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'locked'); ?>
			<?= $form->dropDownList($model,'locked',array('0' => Yii::t('BbiiModule.bbii','No'), '1' => Yii::t('BbiiModule.bbii','Yes'))); ?>
			<?= $form->error($model,'locked'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'sticky'); ?>
			<?= $form->dropDownList($model,'sticky',array('0' => Yii::t('BbiiModule.bbii','No'), '1' => Yii::t('BbiiModule.bbii','Yes'))); ?>
			<?= $form->error($model,'sticky'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'global'); ?>
			<?= $form->dropDownList($model,'global',array('0' => Yii::t('BbiiModule.bbii','No'), '1' => Yii::t('BbiiModule.bbii','Yes'))); ?>
			<?= $form->error($model,'global'); ?>
		</div>
		
		<div class="row">
			<?= $form->hiddenField($model, 'id'); ?>
		</div>
	
	<?php $this->endWidget(); ?>
</div><!-- form -->	
