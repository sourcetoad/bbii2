<?php

use sourcetoad\bbii2\models\BbiiForum;

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\Dialog;
use yii\jui\Sortable;
use yii\web\View;
use yii\web\UrlManager;

/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $category[] BbiiForum  */

/* $this->context->bbii_breadcrumbs = array(
    Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
    Yii::t('BbiiModule.bbii', 'Settings') => array('setting/index'),
    Yii::t('BbiiModule.bbii', 'Forum layout'),
); */

// @depricated 2.5.0 By Yii2.x
/*\Yii::$app->clientScript->registerScript('confirmation', "
var confirmation = new Array();
confirmation[0] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this category?') . "';
confirmation[1] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this forum?') . "';
", CClientScript::POS_BEGIN);*/

$script = "
var confirmation = new Array();
confirmation[0] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this category?') . "';
confirmation[1] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this forum?') . "';";
$this->registerJs($script, View::POS_READY);

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
    Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
    Yii::t('BbiiModule.bbii', 'Settings') => array('setting/index'),
    Yii::t('BbiiModule.bbii', 'Moderators')
);
?>
<div id = "bbii-wrapper" class="well clearfix">
    <?php echo $this->render('template/_header'); ?>
    
    <div class="bbii-box-top"><?php echo Yii::t('BbiiModule.bbii', 'Add category or forum'); ?></div>
    
    <div class="form">

    <?php // @depricated 2.4 Kept for referance
    /* $form=$this->beginWidget('ActiveForm', array(
        'id' => 'bbii-forum-form',
        'enableAjaxValidation' => false,
    ));*/ ?>

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation'   => false,
        'enableClientValidation' => false,
        'id'                     => 'forum-form',
    ]); ?>
    
        <?php echo $form->errorSummary($model); ?>
        
        <div>
            <?php //echo $form->labelEx($model,'name'); ?>
            <?php echo $form->field($model,'name')->textInput(); ?>
            <?php //echo $form->error($model,'name'); ?>
        </div>
        
        <div>
            <?php //echo $form->labelEx($model,'subtitle'); ?>
            <?php echo $form->field($model,'subtitle')->textInput(); ?>
            <?php //echo $form->error($model,'subtitle'); ?>
        </div>

        <div>
            <div class="form-group field-bbiiforum-cateogry">
                <label class="control-label" for="name">Category / Forum</label>
                <?php //echo $form->labelEx($model,'type'); ?>
                <?php echo Html::activeDropDownList(
                    $model,
                    'type',
                    array(
                        '0' => Yii::t('BbiiModule.bbii', 'Category'),
                        '1' => Yii::t('BbiiModule.bbii', 'Forum')
                    ),
                    array(
                        'class'    => 'form-control',
                        'id'       => 'cateogry',
                        'selected' => 1,
                    )
                ); ?>
                <?php //echo $form->error($model,'type'); ?>
            </div>
        </div>

        <div>
            <div class="form-group field-bbiiforum-cat_id">
                <label class="control-label" for="name">Category</label>
                <?php //echo $form->labelEx($model,'cat_id'); ?>
                <?php echo Html::activeDropDownList(
                    $model,
                    'cat_id',
                    ArrayHelper::map(BbiiForum::find()->categories()->all(), 'id', 'name'),
                    array(
                        'class'  => 'form-control',
                        'id'     => 'cat_id',
                        'prompt' => 'None',
                    )
                ); ?>
                <?php //echo $form->error($model,'cat_id'); ?>
            </div>
        </div>
        
        <div class="button">
            <?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Save'), array('class' => 'btn btn-success')); ?>
        </div>
        
    <?php ActiveForm::end(); ?>
    
    </div><!-- form -->    
    
    
    <div class="bbii-box-top"><?php echo Yii::t('BbiiModule.bbii', 'Forum layout'); ?></div>
    <div class="sortable">
    <?php
        $items = array();
        foreach($category as $data) {
            $forum = BbiiForum::find()->where(["cat_id" => $data->getAttribute('id')])->sorted()->forum()->all();
            $items['cat_'.$data->id] = $this->render('_category', array(
                'forum' => $forum,
                'data'  => $data,
            ), true);
        }
        /*
        $this->widget('zii.widgets.jui.CJuiSortable', array(
            'id' => 'sortcategory',
            'items' => $items,
            'htmlOptions' => array('style' => 'list-style:none;;margin-top:1px'),
            'theme' => $this->module->juiTheme,
            'options' => array(
                'delay' => '100',
                'update' => 'js:function(){Sort(this,"' . $this->createAbsoluteUrl('setting/ajaxSort') . '");}',
            ),
        ));*/

        echo Sortable::widget([
            'clientEvents'  => [
                'update' => 'function( event, ui ) {

                     var arr = new Array();
                    $(".ui-sortable-handle table").each(function() {
                        arr.push( $(this).data("id"));
                    });

                    Sort(this,"' . \Yii::$app->urlManager->createAbsoluteUrl(['forum/setting/ajaxsort']) . '");
                }',
            ],
            'clientOptions' => ['cursor' => 'move'],
            'id'            => 'sortcategory',
            'itemOptions'   => ['tag'      => 'li'],
            'items'         => $items,
            'options'       => ['delay'  => '100']
        ]);
    ?>
    </div>
</div>

<?php // @depricated 2.5.0 Kept for Referance
/*
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'      => 'dlgEditForum',
    'theme'   => $this->module->juiTheme,
    'options' => array(
        'title' => 'Edit',
        'autoOpen' => false,
        'modal' => true,
        'width' => 800,
        'show' => 'fade',
        'buttons' => array(
            Yii::t('BbiiModule.bbii', 'Delete') => 'js:function(){ deleteForum("' . $this->createAbsoluteUrl('setting/deleteForum') .'"); }',
            Yii::t('BbiiModule.bbii', 'Save') => 'js:function(){ saveForum("' . $this->createAbsoluteUrl('setting/saveForum') .'"); }',
            Yii::t('BbiiModule.bbii', 'Cancel') => 'js:function(){ $(this).dialog("close"); }',
        ),
    ),
));

    echo $this->renderPartial('_editForum', array('model' => $model));

$this->endWidget('zii.widgets.jui.CJuiDialog');

// @todo Tried this buyt jQuery UI + Dialog + Yii2 just did not seem to work the way I wanted - DJE : 2015-05-25
Dialog::begin([
    'id'           => 'dlgEditForum',
    'clientOptions' =>  [
        'closeButton' => true,
        'autoOpen' => false,
        // @todo get the resolution for this issue - DJE : 2015-05-21
        'buttons'  => [
            // ['text' => Yii::t('BbiiModule.bbii', 'Cancel'), 'click' => 'function(){cancelForum( $( this ).dialog( "close" ); )}'],
            // ['text' => Yii::t('BbiiModule.bbii', 'Delete'), 'click' => 'function(){deleteForum(' . \Yii::$app->urlManager->createAbsoluteUrl('forum/setting/deleteForum') .'); }'],
            // ['text' => Yii::t('BbiiModule.bbii', 'Save') ,  'click' => 'function(){saveForum(' . \Yii::$app->urlManager->createAbsoluteUrl('forum/setting/saveForum') .'); }'],
        ],
        'modal'    => true,
        'show'     => 'fade',
        'title'    => 'Edit',
        'width'    => 800,
    ],
]);

echo $this->render('_editForum', array(
    'model' => $model,
));

Dialog::end();
*/
