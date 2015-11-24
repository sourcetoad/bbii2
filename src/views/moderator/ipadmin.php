<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\grid\GridView;
use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this IpaddressController */
/* @var $model Ipaddress */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
    Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
    Yii::t('BbiiModule.bbii', 'Blocked IP'),
);
?>

<div id = "bbii-wrapper" class="well clearfix">
    <?php echo $this->render('_header', array('item' => $item)); ?>

    <?php // @depricated 2.1.5 Kept for referance
    /*$this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'ipaddress-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'columns' => array(
            'ip',
            'address',
            'count',
            'create_time',
            'update_time',
            array(
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => array(
                    'delete' => array(
                        'url' => 'array("moderator/ipDelete", "id" => $data->id)',
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
            'ip',
            'address',
            'count',
            'create_time',
            'update_time',
            array(
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => array(
                    'delete' => array(
                        'url' => 'array("moderator/ipDelete", "id" => $data->id)',
                        'imageUrl' => $assets->baseUrl.'/images/delete.png',
                        'options' => array('style' => 'margin-left:5px;'),
                    ),
                )
            )),
        'dataProvider' => $model->search(),
        'id'           => 'member-grid',
    )); ?>
</div>