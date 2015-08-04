<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this SettingController */
/* @var $model BbiiMembergroup */
/* @var $form ActiveForm */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings') => array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Moderators')
);

?>

<div id="bbii-wrapper" class="well clearfix">

    <?php echo $this->render('../template/_header'); ?>

    <br />

    <div class = "form">

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation'   => false,
            'enableClientValidation' => false,
            'id'                     => 'edit-spider-form',
        ]); ?>

            <?php echo $form->errorSummary($model); ?>

            <div>
                <?php echo $form->field($model,'name')->textInput(array('size' => 25)); ?>
            </div>

            <div>
                <?php echo $form->field($model,'user_agent')->textInput(array('size' => 70)); ?>
            </div>

            <div>
                <?php echo $form->field($model,'hits')->textInput(array('size' => 11)); ?>
            </div>

            <div>
                <?php echo $form->field($model,'id')->hiddenInput()->label(false); ?>
            </div>

            <div class = "button">
                <?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Save'), array('class' => 'btn btn-success btn-lg')); ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div><!-- form -->

</div>
