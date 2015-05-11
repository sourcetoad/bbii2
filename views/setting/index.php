<?php
/* @var $this ForumController */
/* @var $model BbiiSetting */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Settings'), 'url' => array('setting/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Forum layout'), 'url' => array('setting/layout')),
	array('label' => Yii::t('BbiiModule.bbii', 'Member groups'), 'url' => array('setting/group')),
	array('label' => Yii::t('BbiiModule.bbii', 'Moderators'), 'url' => array('setting/moderator')),
	array('label' => Yii::t('BbiiModule.bbii', 'Webspiders'), 'url' => array('setting/spider')),
);
?>
<div id="bbii-wrapper">
	<?= $this->renderPartial('_header', array('item' => $item)); ?>
	
	<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'bbii-setting-form',
		'enableAjaxValidation' => false,
	)); ?>

		<p class="note"><?= Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

		<?= $form->errorSummary($model); ?>

		<div class="row odd">
			<?= Html::label(Yii::t('BbiiModule.bbii', 'Forum name'), false); ?>
			<?= Html::img($this->module->getRegisteredImage('info.png'), 'Information', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum name is set by the module parameter "forumTitle".'))); ?>
			<?= $this->module->forumTitle; ?>
		</div>

		<div class="row even">
			<?= Html::label(Yii::t('BbiiModule.bbii', 'Forum language'), false); ?>
			<?= Html::img($this->module->getRegisteredImage('info.png'), 'Information', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum language is set by the application parameter "language".'))); ?>
			<?= Yii::$app->language; ?>
		</div>

		<div class="row">
			<?= Html::label(Yii::t('BbiiModule.bbii', 'Forum timezone'), false); ?>
			<?= Html::img($this->module->getRegisteredImage('info.png'), 'Information', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum timezone is set by the PHP.ini parameter "date.timezone".'))); ?>
			<?= date_default_timezone_get(); ?>
		</div>

		<div class="row">
			<?= $form->labelEx($model,'contact_email'); ?>
			<?= $form->textField($model,'contact_email',array('size' => 60,'maxlength' => 255)); ?>
			<?= $form->error($model,'contact_email'); ?>
		</div>

		<div class="row buttons">
			<?= Html::submitButton(Yii::t('BbiiModule.bbii', 'Save')); ?>
		</div>

	<?php $this->endWidget(); ?>

	</div><!-- form -->	
</div>