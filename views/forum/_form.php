<?php

use yii\bootstrap\ActiveForm;

use yii\helpers\Html;

/* @var $this ForumController */
/* @var $post BbiiPost */
/* @var $form ActiveForm */
?>
<div class="well clearfix">
    <div class = "form">
        <?php //@deprecated 2.7.5 Kept for referance
        /* $form = $this->beginWidget('ActiveForm', array(
            'id' => 'create-topic-form',
            'enableAjaxValidation' => false,
        )); ?>
            <?php echo $form->errorSummary($post); ?>

            <div class = "row">
                <?php echo $form->labelEx($post,'subject'); ?>
                <?php echo $form->textField($post,'subject',array('size' => 100,'maxlength' => 255,'style' => 'width:99%;')); ?>
                <?php echo $form->error($post,'subject'); ?>
            </div>

            <div class = "row">
                <?php $this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
                    'model' => $post,
                    'attribute' => 'content',
                    'autoLanguage' => false,
                    'height' => 400,
                    'toolbar' => $this->module->editorToolbar,
                    'skin' => $this->module->editorSkin,
                    'uiColor' => $this->module->editorUIColor,
                    'contentsCss' => $this->module->editorContentsCss,
                )); ?>
                <?php echo $form->error($post,'content'); ?>
            </div>

            <?php if (!$post->isNewRecord): ?>
                <div class = "row">
                    <?php echo $form->labelEx($post,'change_reason'); ?>
                    <?php echo $form->textField($post,'change_reason',array('size' => 100,'maxlength' => 255,'style' => 'width:99%;')); ?>
                    <?php echo $form->error($post,'change_reason'); ?>
                </div>
            <?php endif; ?>

            <div class = "row button">
                <?php echo $form->hiddenField($post, 'forum_id'); ?>
                <?php echo $form->hiddenField($post, 'topic_id'); ?>
                <?php echo Html::submitButton(($post->isNewRecord)?Yii::t('BbiiModule.bbii','Create'):Yii::t('BbiiModule.bbii','Save'), array('class' => 'bbii-topic-button')); ?>
            </div>

        <?php $this->endWidget();*/ ?>

        <?php $form = ActiveForm::begin([
                'enableAjaxValidation'   => false,
                'enableClientValidation' => false,
                'id'                     => 'create-topic-form',
        ]); ?>

            <?php echo $form->errorSummary($post); ?>

            <div>
                <?php echo $form->field($post,'subject')->textInput(); ?>
            </div>

            <div>
                <?php //echo $form->field($post, 'content')->textArea(); ?>
                <?php echo $form->field($post, 'content')->widget(
                    \yii\imperavi\Widget::className(),
                    [
                        //'plugins' => ['fullscreen', 'fontcolor', 'video'],
                        'options' => [
                            'buttonSource'    => true,
                            'convertDivs'     => true,
                            //'imageUpload'   => \Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi']),
                            //'maxHeight'     => 400,
                            //'minHeight'     => 400,
                            'removeEmptyTags' => true,
                            'name'            => 'content'
                        ]
                    ]
                ) ?>
            </div>

            <?php if ($this->context->isModerator()) { ?>
                <div>
                    <strong><?php echo Yii::t('BbiiModule.bbii', 'Sticky'); ?>:</strong><?php echo Html::checkbox('sticky'); ?><br />
                    <strong><?php echo Yii::t('BbiiModule.bbii', 'Global'); ?>:</strong><?php echo Html::checkbox('global'); ?><br />
                    <strong><?php echo Yii::t('BbiiModule.bbii', 'Locked'); ?>:</strong><?php echo Html::checkbox('locked'); ?><br />
                </div>
            <?php }; ?>

            <?php if (!$post->isNewRecord) { ?>
                <diV>
                    <?php echo $form->field($post,'change_reason')->textInput(array('size' => 100,'maxlength' => 255,'style' => 'width:99%;')); ?>
                </div>
            <?php }; ?>

            <div>
                <?php echo $form->field($post, 'forum_id')->hiddenInput()->label(false); ?>
                <?php echo $form->field($post, 'topic_id')->hiddenInput()->label(false); ?>
                <?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Save'), array('class' => 'btn btn-success btn-lg')); ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div><!-- form -->
</div>
