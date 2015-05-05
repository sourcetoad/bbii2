<?php
/* @var $this MemberController */
/* @var $model MailForm */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Members')=>array('member/index'),
	Yii::t('BbiiModule.bbii', 'Send e-mail')
);

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum'), 'url'=>array('forum/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Members'), 'url'=>array('member/index')),
);
?>
<div id="bbii-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<div class="bbii-box-top"><?php echo Yii::t('BbiiModule.bbii', 'Send e-mail to') . ' ' . $model->member_name; ?></div>

	<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'bbii-mail-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<p class="note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'subject'); ?>
			<?php echo $form->textField($model,'subject',array('size'=>80,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'subject'); ?>
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
			<?php echo $form->error($model,'body'); ?>
		</div>

		<div class="row buttons">
			<?php echo $form->hiddenField($model,'member_id'); ?>
			<?php echo $form->hiddenField($model,'member_name'); ?>
			<?php echo CHtml::submitButton(Yii::t('BbiiModule.bbii', 'Send')); ?>
		</div>

	<?php $this->endWidget(); ?>

	</div><!-- form -->
</div>