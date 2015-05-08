<?php
/* @var $this ForumController */
/* @var $model BbiiMessage */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-form',
	'enableAjaxValidation'=>true,
)); ?>

	<div class="row">
		<?= Html::label(Yii::t('BbiiModule.bbii','Short explanation'), 'Bbii_content'); ?>
		<?php $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
			'model'=>$model,
			'attribute'=>'content',
			'autoLanguage'=>false,
			'height'=>'200px',
			'toolbar'=>array(
				array(
					'Bold', 'Italic', 'Underline', 'RemoveFormat'
				),
			),
			'skin'=>$this->module->editorSkin,
			'uiColor'=>$this->module->editorUIColor,
			'contentsCss'=>$this->module->editorContentsCss,
		)); ?>
		<?= $form->error($model,'content'); ?>
		<?= $form->hiddenField($model, 'post_id'); ?>
		<?= Html::hiddenField('url', $this->createAbsoluteUrl('message/sendReport')); ?>
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->