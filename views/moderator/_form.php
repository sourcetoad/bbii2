<?php
/* @var $this ModeratorController */
/* @var $model MailForm */
/* @var $form ActiveForm */
?>

<h2><?php echo Yii::t('BbiiModule.bbii','Send mail to multiple forum members'); ?></h2>

<?php
foreach (\Yii::$app->session->getAllFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
\Yii::$app->session->removeAllFlashes();
?>

<div class = "form">

<?php /* $form = $this->beginWidget('ActiveForm', array(
    'id' => 'bbii-mail-form',
    'enableAjaxValidation' => false,
)); ?>

    <?php //<p class = "note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class = "required">*</span> are required.'); ? ></p> ?>

    <?php echo $form->errorSummary($model); ?>
        
    <div class = "row">
        <?php echo Html::label(Yii::t('BbiiModule.bbii','Member groups'), 'member_id'); ?>
        <?php echo Html::dropDownList($model, 'member_id', ArrayHelper::map(BbiiMembergroup::find()->findAll(), 'id', 'name'), array('empty' => Yii::t('BbiiModule.bbii','All members')));  ?>
        <?php echo $form->error($model,'member_id'); ?>
    </div>

    <div class = "row">
        <?php echo $form->labelEx($model,'subject'); ?>
        <?php echo $form->textField($model,'subject',array('size' => 80,'maxlength' => 255)); ?>
        <?php echo $form->error($model,'subject'); ?>
    </div>
        
    <div class = "row">
        <?php // @depricated 2.2 Kept for referance
        /*$this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
            'model' => $model,
            'attribute' => 'body',
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
        <?php /*
        echo ExtEditMe::widget(array(
            'model' => $model,
            'attribute' => 'body',
            'autoLanguage' => false,
            'height' => '300px',
            'toolbar' => array(
                array('Bold', 'Italic', 'Underline', 'RemoveFormat'),
                array('TextColor', 'BGColor',),
                '-',
                array('Link', 'Unlink', 'Image'),
                '-',
                array('Blockquote'),
            ),
            'skin'        => $this->module->editorSkin,
            'uiColor'     => $this->module->editorUIColor,
            'contentsCss' => $this->module->editorContentsCss,
        )); ?>
        <?php echo $form->error($model,'body'); ?>
    </div>

    <div class = "row buttons">
        <?php if ($this->module->userMailColumn) { echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Send e-mail'), array('name' => 'email')); } ?>
        <?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Send private message'), array('name' => 'pm')); ?>
    </div>

<?php $this->endWidget();*/ ?>

</div><!-- form -->
