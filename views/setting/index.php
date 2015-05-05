<?php
/* @var $this ForumController */
/* @var $model BbiiSetting */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings'),
);

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Settings'), 'url'=>array('setting/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum layout'), 'url'=>array('setting/layout')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Member groups'), 'url'=>array('setting/group')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Moderators'), 'url'=>array('setting/moderator')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Webspiders'), 'url'=>array('setting/spider')),
);
?>
<div id="bbii-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'bbii-setting-form',
		'enableAjaxValidation'=>false,
	)); ?>

		<p class="note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row odd">
			<?php echo CHtml::label(Yii::t('BbiiModule.bbii', 'Forum name'), false); ?>
			<?php echo Html::img($this->module->getRegisteredImage('info.png'), 'Information', array('style'=>'vertical-align:middle;margin-left:10px','title'=>Yii::t('BbiiModule.bbii', 'The forum name is set by the module parameter "forumTitle".'))); ?>
			<?php echo $this->module->forumTitle; ?>
		</div>

		<div class="row even">
			<?php echo CHtml::label(Yii::t('BbiiModule.bbii', 'Forum language'), false); ?>
			<?php echo Html::img($this->module->getRegisteredImage('info.png'), 'Information', array('style'=>'vertical-align:middle;margin-left:10px','title'=>Yii::t('BbiiModule.bbii', 'The forum language is set by the application parameter "language".'))); ?>
			<?php echo Yii::$app->language; ?>
		</div>

		<div class="row">
			<?php echo CHtml::label(Yii::t('BbiiModule.bbii', 'Forum timezone'), false); ?>
			<?php echo Html::img($this->module->getRegisteredImage('info.png'), 'Information', array('style'=>'vertical-align:middle;margin-left:10px','title'=>Yii::t('BbiiModule.bbii', 'The forum timezone is set by the PHP.ini parameter "date.timezone".'))); ?>
			<?php echo date_default_timezone_get(); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'contact_email'); ?>
			<?php echo $form->textField($model,'contact_email',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'contact_email'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('BbiiModule.bbii', 'Save')); ?>
		</div>

	<?php $this->endWidget(); ?>

	</div><!-- form -->	
</div>