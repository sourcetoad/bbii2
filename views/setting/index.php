<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $model BbiiSetting */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings'),
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('template/_header'); ?>
	
	<div class = "form">

	<?php // @depricated 2.2.0 Kept for referance
	/*$form = $this->beginWidget('ActiveForm', array(
		'enableAjaxValidation' => false,
		'id'                   => 'bbii-setting-form',
	));*/ ?>

	<?php $form = ActiveForm::begin([
		'enableAjaxValidation' => false,
		'id'                   => 'bbii-setting-form',
	]);?>
		<?php // @todo Iterate on forms - DJE : 2015-05-15 ?>

		<?php //<p class = "note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class = "required">*</span> are required.'); ? ></p> ?>

		<?php echo $form->errorSummary($model); ?>

		<div class = "row odd">
			<?php echo Html::label(Yii::t('BbiiModule.bbii', 'Forum name'), false); ?>
			<?php echo Html::img($assets->baseUrl.'/images/info.png', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum name is set by the module parameter "forumTitle".'))); ?>
			<?php echo $this->context->module->forumTitle; ?>
		</div>

		<div class = "row even">
			<?php echo Html::label(Yii::t('BbiiModule.bbii', 'Forum language'), false); ?>
			<?php echo Html::img($assets->baseUrl.'/images/info.png', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum language is set by the application parameter "language".'))); ?>
			<?php echo Yii::$app->language; ?>
		</div>

		<div class = "row odd">
			<?php echo Html::label(Yii::t('BbiiModule.bbii', 'Forum timezone'), false); ?>
			<?php echo Html::img($assets->baseUrl.'/images/info.png', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum timezone is set by the PHP.ini parameter "date.timezone".'))); ?>
			<?php echo date_default_timezone_get(); ?>
		</div>

		<div class = "row even">
			<?php echo $form->field($model, 'contact_email')->label('Contact Email')->textInput(['maxlength' => 255]); ?>
		</div>

		<div class = "row odd buttons">
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Save')); ?>
		</div>

	<?php ActiveForm::end(); ?>
	
	</div><!-- form -->	
</div>