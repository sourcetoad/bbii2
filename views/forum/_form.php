<?php
/* @var $this ForumController */
/* @var $post BbiiPost */
/* @var $form CActiveForm */
?>
<noscript>
<div class="flash-notice">
<?php echo Yii::t('BbiiModule.bbii','Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
</div>
</noscript>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'create-topic-form',
		'enableAjaxValidation'=>false,
	)); ?>
		<?php echo $form->errorSummary($post); ?>
		
		<div class="row">
			<?php echo $form->labelEx($post,'subject'); ?>
			<?php echo $form->textField($post,'subject',array('size'=>100,'maxlength'=>255,'style'=>'width:99%;')); ?>
			<?php echo $form->error($post,'subject'); ?>
		</div>
		
		<div class="row">
		<?php $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
			'model'=>$post,
			'attribute'=>'content',
			'autoLanguage'=>false,
			'height'=>400,
			'toolbar'=>$this->module->editorToolbar,
			'skin'=>$this->module->editorSkin,
			'uiColor'=>$this->module->editorUIColor,
			'contentsCss'=>$this->module->editorContentsCss,
		)); ?>
		<?php echo $form->error($post,'content'); ?>
	</div>
	
	<?php if(!$post->isNewRecord): ?>
		<div class="row">
			<?php echo $form->labelEx($post,'change_reason'); ?>
			<?php echo $form->textField($post,'change_reason',array('size'=>100,'maxlength'=>255,'style'=>'width:99%;')); ?>
			<?php echo $form->error($post,'change_reason'); ?>
		</div>
	<?php endif; ?>
		
	<div class="row button">
		<?php echo $form->hiddenField($post, 'forum_id'); ?>
		<?php echo $form->hiddenField($post, 'topic_id'); ?>
		<?php echo CHtml::submitButton(($post->isNewRecord)?Yii::t('BbiiModule.bbii','Create'):Yii::t('BbiiModule.bbii','Save'), array('class'=>'bbii-topic-button')); ?>
	</div>
	
	<?php $this->endWidget(); ?>
</div><!-- form -->	
