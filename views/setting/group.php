<?php

use frontend\modules\bbii\models\BbiiMembergroup;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Dialog;

/* @var $this SettingController */
/* @var $model BbiiMembergroup */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings') => array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Member groups')
);

/*Yii::$app->clientScript->registerScript('confirmation', "
var confirmation = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this member group?') . "'
", CClientScript::POS_BEGIN);*/
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('template/_header'); ?>
	
    <p>
        <?= Html::a('Create Member Group', ['createmembergroup'], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?php // @depricated 2.3 Kept for referance
	/* $this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'membergroup-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'columns' => array(
			array(
				'name' => 'id',
				// 'visible' => false,
			),
			'name',
			'description',
			'min_posts',
			array(
				'name' => 'color',
				'type' => 'raw',
				'value' => '"<span style = \"font-weight:bold;color:#$data->color\">$data->color</span>"',
			),
			'image',
			array(
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update}',
				'buttons' => array(
					'update' => array(
						'click' => 'js:function($data) { editMembergroup($(this).closest("tr").children("td:first").text(), "' . Yii::$app->urlManager->createAbsoluteUrl('setting/getMembergroup') .'");return false; }',
					),
				)
			),
		),
	));*/ ?>

	<?php echo GridView::widget(array(
		'columns'      => array(
			array(
				'label'   => 'id',
				'visible' => false,
			),
			'name',
			'description',
			'min_posts',
			/*array(
				'format' => 'raw',
				'label'  => 'color',
				'value'  => function ($data) { return '<p style="font-weight: bold;color: #'.$data->color.'">'.$data->color.'</p>'; },
			),
			'image',*/
			
			// @todo use prop Yii2 CRUD to view/update/delete forum groups.
			[
				'buttons'=>[
					'update' => function ($url, $model) {     
						return Html::a(
							'<span class="glyphicon glyphicon-pencil"></span>',
							'updatemembergroup?id='.$model->id,
							['title' => Yii::t('yii', 'Update')]
						);                                
					}
				],
				'class'    => 'yii\grid\ActionColumn',
				'template' => '{update}'
			],
		),
		'dataProvider' => $model->search(),
		'id'           => 'membergroup-grid',
	)); ?>

</div>

<?php // @depricated 2.3 Kept for referance
/* $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id' => 'dlgEditMembergroup',
	'theme' => $this->module->juiTheme,
    'options' => array(
        'title' => 'Edit',
        'autoOpen' => false,
		'modal' => true,
		'width' => 450,
		'show' => 'fade',
		'buttons' => array(
			Yii::t('BbiiModule.bbii', 'Delete') => 'js:function(){ deleteMembergroup("' . Yii::$app->urlManager->createAbsoluteUrl('setting/deleteMembergroup') .'"); }',
			Yii::t('BbiiModule.bbii', 'Save') => 'js:function(){ saveMembergroup("' . Yii::$app->urlManager->createAbsoluteUrl('setting/saveMembergroup') .'"); }',
			Yii::t('BbiiModule.bbii', 'Cancel') => 'js:function(){ $(this).dialog("close"); }',
		),
    ),
));

    echo $this->render('_editMembergroup', array('model' => $model));

$this->endWidget('zii.widgets.jui.CJuiDialog');


Dialog::begin([
	'id'           => 'dlgEditMembergroup',
	'clientOptions'=> [
		'closeButton' => true,
		'autoOpen' => false,
		// @todo get the resolution for this issue - DJE : 2015-05-21
		'buttons'  => [
			// ['text' => Yii::t('BbiiModule.bbii', 'Cancel'), 	'click' => 'js:function(){ $(this).dialog("close"); }'],
			// ['text' => Yii::t('BbiiModule.bbii', 'Delete'), 	'click' => 'js:function(){ deleteMembergroup("' . Yii::$app->urlManager->createAbsoluteUrl('forum/setting/deleteMembergroup') .'"); }'],
			// ['text' => Yii::t('BbiiModule.bbii', 'Save'), 		'click' => 'js:function(){ saveMembergroup("' . Yii::$app->urlManager->createAbsoluteUrl('forum/setting/saveMembergroup') .'"); }'],
		],
		'modal'    => true,
		'show'     => 'fade',
		'title'    => 'Edit',
		'width'    => 800,
	],
]);

echo $this->render('_editMembergroup', array(
	'model' => $model,
));

Dialog::end();
*/
