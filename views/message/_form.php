<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $form CActiveForm */
?>
<noscript>
<div class = "flash-notice">
<?php echo Yii::t('BbiiModule.bbii','Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
</div>
</noscript>

<div class = "form">

	<?php // @depricated 2.5.0 Kept for referance
	/*$form = $this->beginWidget('CActiveForm', array(
		'id' => 'message-form',
		'enableAjaxValidation' => false,
	));*/ ?>

	<?php $form = ActiveForm::begin([
		'enableAjaxValidation' => false,
		'id'                   => 'message-form',
	]); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class = "row">
	<?php if (Yii::$app->controller->action->id == 'create') {
		// echo $form->labelEx($model,'sendto');
		// @todo iterate on this - DJE : 2015-05-19
		/*$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
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
		)); */
		} else{
			echo $form->label($model,'sendto');
			echo '<strong>'.Html::encode($model->search).'</strong>';
		} ?>
		<?php echo $form->field($model, 'sendto'); ?>
		<?php // echo $form->error($model,'sendto'); ?>
	</div>
	
	<div class = "row">
		<?php // echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->field($model,'subject')->textInput(array('size' => 100,'maxlength' => 255)); ?>
		<?php // echo $form->error($model,'subject'); ?>
	</div>
	
	<div class = "row">
		<?php // @todo iterate on this - DJE : 2015-05-19
		/* $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
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
		));*/ ?>
		<?php echo $form->field($model,'content')->textArea(['rows' => '6']);  ?>
		<?php // echo $form->error($model,'content'); ?>
	</div>
	
	<div class = "row buttons">
		<?php echo $form->field($model,'type')->input('hidden')->label(false);  ?>
		<?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Send')); ?>
	</div>

	<?php ActiveForm::end(); ?>

</div><!-- form -->