<?php

use sourcetoad\bbii2\models\BbiiPost;
use sourcetoad\bbii2\models\BbiiMessage;

use yii\grid\GridView;
use yii\helpers\Html;

use sourcetoad\bbii2\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiMessage */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
    Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
    Yii::t('BbiiModule.bbii', 'Reports'),
);
?>

<div id = "bbii-wrapper" class="well clearfix">
    <?php echo $this->render('_header', array('item' => $item)); ?>

    <?php // @depricated 2.2 Kept for referance
    /*$this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'message-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'columns' => array(
            array(
                'name' => 'sendfrom',
                'value' => '$data->sender->member_name',
                'filter' => false,
            ),
            'subject',
            array(
                'name' => 'content',
                'type' => 'html',
            ),
            'create_time',
            array(
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{reply}{delete}',
                'buttons' => array(
                    'view' => array(
                        'url' => 'array("forum/topic", "id" => $data->forumPost->topic_id, "nav" => $data->post_id)',
                        'label' => Yii::t('BbiiModule.bbii','Go to post'),
                        'imageUrl' => $assets->baseUrl.'/images/goto.png',
                    ),
                    'reply' => array(
                        'url' => 'array("message/reply", "id" => $data->id)',
                        'label' => Yii::t('BbiiModule.bbii','Reply'),
                        'imageUrl' => $assets->baseUrl.'reply.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                    'delete' => array(
                        'url' => 'array("message/delete", "id" => $data->id)',
                        'imageUrl' => $assets->baseUrl.'/images/delete.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                )
            ),
        ),
    ));*/ ?>

    <?php
    echo GridView::widget(array(
        'columns'      => array(
            array(
                'filter' => false,
                'header' => 'Send From',
                'value'  => '$data->sender->member_name',
            ),
            'subject',
            array(
                'header' => 'Content',
                'format'   => 'html',
            ),
            'create_time',
            array(
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view}{reply}{delete}',
                'buttons'  => array(
                    'view' => array(
                        'url' => 'array("forum/topic", "id" => $data->forumPost->topic_id, "nav" => $data->post_id)',
                        'label' => Yii::t('BbiiModule.bbii','Go to post'),
                        'imageUrl' => $assets->baseUrl.'/images/goto.png',
                    ),
                    'reply' => array(
                        'url' => 'array("message/reply", "id" => $data->id)',
                        'label' => Yii::t('BbiiModule.bbii','Reply'),
                        'imageUrl' => $assets->baseUrl.'reply.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                    'delete' => array(
                        'url' => 'array("message/delete", "id" => $data->id)',
                        'imageUrl' => $assets->baseUrl.'/images/delete.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                )
            ),
        ),
        'dataProvider' => $model->search(),
        'id'           => 'message-grid',
    )); ?>
</div>