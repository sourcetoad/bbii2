﻿<?php

use sourcetoad\bbii2\models\BbiiForum;
use sourcetoad\bbii2\models\BbiiMessage;
use sourcetoad\bbii2\models\BbiiPost;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use sourcetoad\bbii2\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiPost */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
    Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
    Yii::t('BbiiModule.bbii', 'Posts'),
);

/*\Yii::$app->clientScript->registerScript('setAutocomplete', "
function setAutocomplete(id, data) {
    $('#BbiiPost_search').autocomplete({
        source: '" . $this->createUrl('member/members') . "',
        select: function(event,ui) {
            $('#BbiiPost_search').val(ui.item.label);
            $('#bbii-post-grid').yiiGridView('update', { data: $(this).serialize() });
            return false;
        },
        'minLength': 2,
        'delay': 200
    });
}
");
*/
?>

<div id = "bbii-wrapper" class="well clearfix">
    <?php echo $this->render('_header', array('item' => $item)); ?>

    <?php // @depricated 2.1.5 Kept for referance
    /*$dataProvider = $model->search();
    $dataProvider->setPagination(array('pageSize' => 20));
    $dataProvider->setSort(array('defaultOrder' => 'create_time DESC'));
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'bbii-post-grid',
        'dataProvider' => $dataProvider,
        'filter' => $model,
        'afterAjaxUpdate' => 'setAutocomplete',
        'columns' => array(
            array(
                'name' => 'forum_id',
                'value' => '$data->forum->name',
                'filter' => ArrayHelper::map(BbiiForum::getAllForumOptions(), 'id', 'name', 'group'),
            ),
            'subject',
            array(
                'name' => 'search',
                'filter'  => $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                    'attribute' => 'search',
                    'model' => $model,
                    'sourceUrl' => array('member/members'),
                    'theme' => $this->module->juiTheme,
                    'options' => array(
                        'minLength' => 2,
                        'delay' => 200,
                        'select' => 'js:function(event, ui) { 
                            $("#BbiiPost_search").val(ui.item.label);
                            $("#bbii-post-grid").yiiGridView("update", { data: $(this).serialize() });
                            return false;
                        }',
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;',
                    ),
                ), true),
                'value' => '$data->poster->member_name',
            ),
            'ip',
            'create_time',
            array(
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => array(
                    'view' => array(
                        'url' => 'array("forum/topic", "id" => $data->topic_id, "nav" => $data->id)',
                        'imageUrl' => $assets->baseUrl.'view.png',
                    ),
                    'update' => array(
                        'url' => 'array("forum/update", "id" => $data->id)',
                        'label' => Yii::t('BbiiModule.bbii','Update'),
                        'imageUrl' => $assets->baseUrl.'/images/update.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                    'delete' => array(
                        'imageUrl' => $assets->baseUrl.'/images/delete.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                )
            ),
        ),
    ));*/ ?>
    <?php
    $dataProvider = $model->search();
    echo GridView::widget(array(
        'id'              => 'bbii-post-grid',
        'dataProvider'    => $dataProvider,
        'columns'         => array(
            array(
                'filter' => ArrayHelper::map(BbiiForum::getAllForumOptions(), 'id', 'name', 'group'),
                'header' => 'Forum Name',
                'value'  => 'forum.name',
            ),

            'subject',
            'create_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ),
    )); ?>
</div>