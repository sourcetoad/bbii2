<?php
/* @var $this ModeratorController */
/* @var $model MailForm */
/* @var $form CActiveForm */
?>

<h2><?= Yii::t('BbiiModule.bbii','Send mail to multiple forum members'); ?></h2>
<?php if(Yii::$app->user->hasFlash('success')): ?>

<div class="flash-success">
	<?= Yii::$app->user->getFlash('success'); ?>
</div>

<?php endif; ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'bbii-mail-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?= Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?= $form->errorSummary($model); ?>
		
	<div class="row">
		<?= Html::label(Yii::t('BbiiModule.bbii','Member groups'), 'member_id'); ?>
		<?= $form->dropDownList($model, 'member_id', Html::listData(BbiiMembergroup::model()->findAll(), 'id', 'name'), array('empty'=>Yii::t('BbiiModule.bbii','All members')));  ?>
		<?= $form->error($model,'member_id'); ?>
	</div>

	<div class="row">
		<?= $form->labelEx($model,'subject'); ?>
		<?= $form->textField($model,'subject',array('size'=>80,'maxlength'=>255)); ?>
		<?= $form->error($model,'subject'); ?>
	</div>
		
	<div class="row">
		<?php $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
			'model'=>$model,
			'attribute'=>'body',
			'autoLanguage'=>false,
			'height'=>'300px',
			'toolbar'=>array(
				array(
					'Bold', 'Italic', 'Underline', 'RemoveFormat'
				),
				array(
						'TextColor', 'BGColor',
				),
				'-',
				array('Link', 'Unlink', 'Image'),
				'-',
				array('Blockquote'),
			),
			'skin'=>$this->module->editorSkin,
			'uiColor'=>$this->module->editorUIColor,
			'contentsCss'=>$this->module->editorContentsCss,
		)); ?>
		<?= $form->error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php if($this->module->userMailColumn) { echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Send e-mail'), array('name'=>'email')); } ?>
		<?= Html::submitButton(Yii::t('BbiiModule.bbii', 'Send private message'), array('name'=>'pm')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
