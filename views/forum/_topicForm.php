<?php
/* @var $this ForumController */
/* @var $model BbiiTopic */
/* @var $form CActiveForm */
?>

<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'update-topic-form',
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
			'validateOnChange'=>false,
		)
	)); ?>
		<?php echo $form->errorSummary($model); ?>
		
		<div class="row">
			<?php echo $form->labelEx($model,'forum_id'); ?>
			<?php echo $form->dropDownList($model,'forum_id',CHtml::listData(BbiiForum::model()->forum()->findAll(), 'id', 'name'), array('onchange'=>'refreshTopics(this, "' . $this->createAbsoluteUrl('moderator/refreshTopics') . '")')); ?>
			<?php echo $form->error($model,'forum_id'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'merge'); ?>
			<?php echo $form->dropDownList($model,'merge',array()); ?>
			<?php echo $form->error($model,'merge'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>100,'maxlength'=>255,'style'=>'width:99%;')); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'locked'); ?>
			<?php echo $form->dropDownList($model,'locked',array('0'=>Yii::t('BbiiModule.bbii','No'), '1'=>Yii::t('BbiiModule.bbii','Yes'))); ?>
			<?php echo $form->error($model,'locked'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'sticky'); ?>
			<?php echo $form->dropDownList($model,'sticky',array('0'=>Yii::t('BbiiModule.bbii','No'), '1'=>Yii::t('BbiiModule.bbii','Yes'))); ?>
			<?php echo $form->error($model,'sticky'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'global'); ?>
			<?php echo $form->dropDownList($model,'global',array('0'=>Yii::t('BbiiModule.bbii','No'), '1'=>Yii::t('BbiiModule.bbii','Yes'))); ?>
			<?php echo $form->error($model,'global'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->hiddenField($model, 'id'); ?>
		</div>
	
	<?php $this->endWidget(); ?>
</div><!-- form -->	
