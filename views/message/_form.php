<?php

use frontend\modules\bbii\models\BbiiMember;

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $form ActiveForm */
?>
<div class = "form">

    <?php // @depricated 2.5.0 Kept for referance
    /*$form = $this->beginWidget('ActiveForm', array(
        'id' => 'message-form',
        'enableAjaxValidation' => false,
    ));*/ ?>

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation'   => false,
        'enableClientValidation' => false,
        'id'                     => 'message-form',
    ]); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class = "row">
      <div class="col col-md-12">
           <?php if (\Yii::$app->controller->action->id == 'create') {
                // echo $form->labelEx($model,'sendto');
                // @todo iterate on this - DJE : 2015-05-19
                /*$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                    'attribute' => 'search',
                    'model'     => $model,
                    'sourceUrl' => array('member/members'),
                    'theme'     => $this->module->juiTheme,
                    'options'   => array(
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


                echo '<label class="control-label" for="bbiimessage-sendto">Send To</label>';

                echo AutoComplete::widget([
                    'clientOptions' => [
                        'source'    => BbiiMember::find()->select(['member_name as value', 'member_name as label','id as id'])->asArray()->all(),
                        'autoFill'  => true,
                        'minLength' => '4',
                        'select'    => new JsExpression("function( event, ui ) { $('#bbiimessage-sendto').val(ui.item.id); }")
                    ],
                    'id'      => 'bbiimessage-sendto',
                    'name'    => 'BbiiMessage[sendto]',
                    'options' => ['class' => 'form-control']
                ]); */
                echo $form->field($model, 'sendto')->textInput();

            } else{
                echo $form->label($model,'sendto');
                echo '<strong>'.Html::encode($model->search).'</strong>';
            } ?>
            <?php // echo $form->error($model,'sendto'); ?>
        </div>
    </div>
    
    <div class = "row">
        <div class="col col-md-12">
            <?php // echo $form->labelEx($model,'subject'); ?>
            <?php echo $form->field($model,'subject')->textInput(array('size' => 100,'maxlength' => 255)); ?>
            <?php // echo $form->error($model,'subject'); ?>
        </div>
    </div>
    
    <div class = "row">
        <div class="col col-md-12">
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
            <?php //echo $form->field($model, 'content')->textArea(['rows' => '6']);  ?>
            <?php // echo $form->error($model,'content'); ?>
            <?php echo $form->field($model, 'content')->widget(
                \yii\imperavi\Widget::className(),
                [
                    //'plugins' => ['fullscreen', 'fontcolor', 'video'],
                    'options' => [
                        'buttonSource'    => true,
                        'convertDivs'     => true,
                        //'imageUpload'     => \Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi']),
                        //'maxHeight'       => 400,
                        //'minHeight'       => 400,
                        'removeEmptyTags' => true,
                        'name'          => 'content'
                    ]
                ]
            ) ?>
        </div>
    </div>
    
    <div class = "row buttons">
        <div class="col col-md-12">
            <?php echo $form->field($model, 'type')->input('hidden')->label(false);  ?>
            <?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Send'), array('class' => 'btn btn-success btn-lg')); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div><!-- form -->
