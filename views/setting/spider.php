<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\UrlManager;

/* @var $this SettingController */
/* @var $model BbiiSpider */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') 		=> array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings') 	=> array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Webspiders')
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Settings'), 	'url' => array('setting/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Forum layout'), 'url' => array('setting/layout')),
	array('label' => Yii::t('BbiiModule.bbii', 'Member groups'),'url' => array('setting/group')),
	array('label' => Yii::t('BbiiModule.bbii', 'Moderators'), 	'url' => array('setting/moderator'))
);

/*Yii::$app->clientScript->registerScript('confirmation', "
var confirmation = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this webspider?') . "'
", CClientScript::POS_BEGIN);*/
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
    <p>
        <?= Html::a('Create Web Spider', ['createspider'], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?php // @depricated 2.3.0 Kept for referance
	/* $this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'spider-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'columns' => array(
			array(
				'name' => 'name',
				'type' => 'raw',
				'value' => 'Html::a($data->name, $data->url, array("target" => "_new")) . "<span style = \"display:none;\">{$data->id}</span>"',
			),
			'user_agent',
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Hits'),
				'value' => '$data->hits',
				'htmlOptions' => array('style' => 'text-align:center;'),
			),
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Last visit'),
				'value' => 'DateTimeCalculation::full($data->last_visit)',
			),
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}',
				'buttons' => array(
					'update' => array(
						'click' => 'js:function($data) { BBiiSetting.EditSpider($(this).closest("tr").children("td:first").children("span").text(), "' . Yii::$app->urlManager->createAbsoluteUrl('setting/getSpider') .'");return false; }',
					),
				)
			),
		),
	));*/ ?>

	<?php echo GridView::widget(array(

		'columns' => array(
			'name',
			'user_agent',
			'hits',
			'last_visit:datetime',

			[
				'buttons'=>[
					'update' => function ($url, $model) {     
						return Html::a(
							'<span class="glyphicon glyphicon-pencil"></span>',
							'updatespider?id='.$model->id,
							['title' => Yii::t('yii', 'Update')]
						);                                
					}
				],
				'class'    => 'yii\grid\ActionColumn',
				'template' => '{update}'
			],
		),
		'dataProvider' => $model,
		'id'           => 'spider-grid',
	)); ?>
</div>

<?php // @depricated 2.3.0 Kept for referance
/* $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id' => 'dlgEditSpider',
	'theme' => $this->module->juiTheme,
    'options' => array(
        'title' => 'Edit',
        'autoOpen' => false,
		'modal' => true,
		'width' => 700,
		'show' => 'fade',
		'buttons' => array(
			Yii::t('BbiiModule.bbii', 'Delete') => 'js:function(){ BBiiSetting.DeleteSpider("' . Yii::$app->urlManager->createAbsoluteUrl('setting/deleteSpider') .'"); }',
			Yii::t('BbiiModule.bbii', 'Save') => 'js:function(){ BBiiSetting.SaveSpider("' . Yii::$app->urlManager->createAbsoluteUrl('setting/saveSpider') .'"); }',
			Yii::t('BbiiModule.bbii', 'Cancel') => 'js:function(){ $(this).dialog("close"); }',
		),
    ),
));

    echo $this->render('_editSpider', array('model' => $model));

$this->endWidget('zii.widgets.jui.CJuiDialog');
*/
