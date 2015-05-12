<?php

use frontend\modules\bbii\AppAsset;

use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $model BbiiSetting */

/*
$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings'),
);
*/

?>
<div id="bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<div class="form">

	<?php $form = ActiveForm::begin([
		'id' => 'bbii-setting-form',
		'options' => [
			'class'                => 'my-form',
			'enableAjaxValidation' => false,
		]
	]);?>

		<p class="note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>

		<?php //echo $form->errorSummary(); ?>

		<div class="row odd">
			<?php echo Html::label(Yii::t('BbiiModule.bbii', 'Forum name'), false); ?>
			<?php echo Html::img($assets->baseUrl.'/images/info.png', 'Information', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum name is set by the module parameter "forumTitle".'))); ?>
			<?php echo $this->context->module->forumTitle; ?>
		</div>

		<div class="row even">
			<?php echo Html::label(Yii::t('BbiiModule.bbii', 'Forum language'), false); ?>
			<?php echo Html::img($assets->baseUrl.'/images/info.png', 'Information', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum language is set by the application parameter "language".'))); ?>
			<?php echo Yii::$app->language; ?>
		</div>

		<div class="row">
			<?php echo Html::label(Yii::t('BbiiModule.bbii', 'Forum timezone'), false); ?>
			<?php echo Html::img($assets->baseUrl.'/images/info.png', 'Information', array('style' => 'vertical-align:middle;margin-left:10px','title' => Yii::t('BbiiModule.bbii', 'The forum timezone is set by the PHP.ini parameter "date.timezone".'))); ?>
			<?php echo date_default_timezone_get(); ?>
		</div>

		<div class="row">
			<?php /*
			<?php echo $form->labelEx($model,'contact_email'); ?>
			<?php echo $form->textField($model,'contact_email',array('size' => 60,'maxlength' => 255)); ?>
			<?php echo $form->error($model,'contact_email'); ?>
			*/ ?>
		</div>

		<div class="row buttons">
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Save')); ?>
		</div>

	<?php ActiveForm::end(); ?>

	</div><!-- form -->	
</div>