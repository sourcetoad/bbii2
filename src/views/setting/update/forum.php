<?php

use sourcetoad\bbii2\models\BbiiForum;
use sourcetoad\bbii2\models\BbiiMembergroup;

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $form ActiveForm */

$booleanDDLOptions = [
    '0' => Yii::t('BbiiModule.bbii', 'No'),
    '1' => Yii::t('BbiiModule.bbii', 'Yes')
];
?>

<div id="bbii-wrapper" class="well clearfix">

    <?php echo $this->render('../template/_header'); ?>

    <div class = "form">

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation'   => false,
            'enableClientValidation' => false,
            'id'                     => 'edit-forum-form',
        ]); ?>

            <?php echo $form->errorSummary($model); ?>

            <div>
                <?php echo $form->field($model,'name')->textInput(); ?>
            </div>

            <div>
                <?php echo $form->field($model,'subtitle')->textInput(); ?>
            </div>

            <?php if ($model->type != 0) { ?>
            <div>
                <div class="form-group field-bbiiforum-cateogry">
                    <label class="control-label" for="name">Category</label>
                    <?php echo Html::activeDropDownList($model, 'cat_id',
                        ArrayHelper::map(BbiiForum::find()->categories()->all(), 'id', 'name'),
                        [
                            'class'  => 'form-control',
                            'id'     => 'cateogry',
                            'prompt' => 'Choose One',
                    ]); ?>
                </div>
            </div>
            <?php }; ?>

            <div>
                <div class="form-group field-bbiiforum-public">
                    <label class="control-label" for="name">Public</label>
                    <?php echo Html::activeDropDownList($model, 'public', $booleanDDLOptions, [
                            'class'  => 'form-control',
                            'id'     => 'public',
                    ]); ?>
                </div>
            </div>

            <div>
                <div class="form-group field-bbiiforum-locked">
                    <label class="control-label" for="name">Locked</label>
                    <?php echo Html::activeDropDownList($model, 'locked', $booleanDDLOptions, [
                            'class'  => 'form-control',
                            'id'     => 'locked',
                    ]); ?>
                </div>
            </div>

            <div>
                <div class="form-group field-bbiiforum-moderated">
                    <label class="control-label" for="name">Moderated</label>
                    <?php echo Html::activeDropDownList($model, 'moderated', $booleanDDLOptions, [
                            'class'  => 'form-control',
                            'id'     => 'moderated',
                    ]); ?>
                </div>
            </div>

            <div>
                <div class="form-group field-bbiiforum-membergroup">
                    <label class="control-label" for="name">Member Group</label>
                    <?php echo Html::activeDropDownList($model, 'membergroup_id',
                        ArrayHelper::map(BbiiMembergroup::find()->specific()->all(), 'id', 'name'),
                        [
                            'class'  => 'form-control',
                            'id'     => 'membergroup',
                    ]); ?>
                </div>
            </div>

            <div>
                <div class="form-group field-bbiiforum-poll">
                    <label class="control-label" for="name">Poll</label>
                    <?php echo Html::activeDropDownList($model, 'poll',
                        [
                            '0' => Yii::t('BbiiModule.bbii', 'No polls'),
                            '1' => Yii::t('BbiiModule.bbii', 'Moderator polls'),
                            '2' => Yii::t('BbiiModule.bbii', 'User polls')
                        ],[
                            'class'    => 'form-control',
                            'disabled' => true,
                            'id'       => 'poll',
                    ]); ?>
                </div>
            </div>
            
            <div>
                <?php echo $form->field($model, 'id')->hiddenInput()->label(false); ?>
                <?php echo $form->field($model, 'type')->hiddenInput()->label(false); ?>
            </div>

            <div class = "button">
                <?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Save'), array('class' => 'btn btn-success btn-lg')); ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div><!-- form -->

</div>