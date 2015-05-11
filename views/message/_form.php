<?php
/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $form CActiveForm */
?>
<noscript>
<div class="flash-notice">
<?= Yii::t('BbiiModule.bbii','Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
</div>
</noscript>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id' => 'message-form',
	'enableAjaxValidation' => false,
)); ?>

	<?= $form->errorSummary($model); ?>

	<div class="row">
	<?php if($this->action->id == 'create'): ?>
		<?= $form->labelEx($model,'sendto'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'attribute' => 'search',
				'model' => $model,
				'sourceUrl' => array('member/members'),
				'theme' => $this->module->juiTheme,
				'options' => array(
					'minLength' => 2,
					'delay' => 200,
					'select' => 'js:function(event, ui) { 
						$("#BbiiMessage_search").val(ui.item.label);
						$("#BbiiMessage_sendto").val(ui.item.value);
						return false;
					}',
				),
				'htmlOptions' => array(
					'style' => 'height:20px;',
				),
			)); 
		?>
	<?php else: ?>
		<?= $form->label($model,'sendto'); ?>
		<strong><?= Html::encode($model->search); ?></strong>
	<?php endif; ?>
		<?= $form->hiddenField($model,'sendto'); ?>
		<?= $form->error($model,'sendto'); ?>
	</div>
	
	<div class="row">
		<?= $form->labelEx($model,'subject'); ?>
		<?= $form->textField($model,'subject',array('size' => 100,'maxlength' => 255)); ?>
		<?= $form->error($model,'subject'); ?>
	</div>
	
	<div class="row">
		<?php $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
			'model' => $model,
			'attribute' => 'content',
			'autoLanguage' => false,
			'height' => '300px',
			'toolbar' => array(
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
			'skin' => $this->module->editorSkin,
			'uiColor' => $this->module->editorUIColor,
			'contentsCss' => $this->module->editorContentsCss,
		)); ?>
		<?= $form->error($model,'content'); ?>
	</div>
	
	<div class="row buttons">
		<?= $form->hiddenField($model,'type'); ?>
		<?= Html::submitButton(Yii::t('BbiiModule.bbii', 'Send')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->