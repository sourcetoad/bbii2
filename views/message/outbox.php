<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\UrlManager;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $count Array */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Outbox'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Inbox') 	.' ('. $count['inbox'] .')', 	'url' => array('message/inbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'Outbox') 	.' ('. $count['outbox'] .')', 	'url' => array('message/outbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'New message'), 								'url' => array('message/create'))
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item, 'count' => $count, 'box' => 'outbox')); ?>

	<?php // @depricated 2.1.5 Kept for referance
	/*$this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'outbox-grid',
		'dataProvider' => $model->search(),
		'rowCssClassExpression' => '($data->read_indicator)?"":"unread"',
		'columns' => array(
			array(
				'name' => 'sendto',
				'value' => '$data->receiver->member_name'
			),
			'subject',
			array(
				'name' => 'create_time',
				'value' => '\Yii::$app->formatter->asDatetime($data->create_time)',
			),
			array(
				'name' => 'type',
				'value' => '($data->type)?Yii::t("bbii", "notification"):Yii::t("bbii", "message")',
			),
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => '$data->id',
						'imageUrl' => $assets->baseUrl.'view.png',
						'click' => 'js:function() { viewMessage($(this).attr("href"), "' . \Yii::$app->urlManager->createAbsoluteUrl('message/view') .'");return false; }',
					),
					'delete' => array(
						'imageUrl' => $assets->baseUrl.'/images/delete.png',
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			),
		),
	));*/ ?>
	<div class="well clearix">
        <?php echo GridView::widget(array(
            'columns'      => array(
                array(
                    'attribute' => 'sendto',
                    'value'     => 'sendto'
                ),
                'subject',
                array(
                    'attribute' => 'create_time',
                    'value'     => 'create_time',
                ),
                array(
                    'attribute' => 'type',
                    'value'     => 'type',
                ),

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}{delete}',
                ]),
            'dataProvider'          => $model->search(),
            'id'                    => 'inbox-grid',
        )); ?>
	   <div id = "bbii-message"></div>
    </div>
</div>
