<?php

use yii\bootstrap\ActiveForm;
use yii\jui\Sortable;
use yii\jui\Dialog;

/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $category[] BbiiForum  */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings') => array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Forum layout'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Settings'), 'url' => array('setting/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Forum layout'), 'url' => array('setting/layout')),
	array('label' => Yii::t('BbiiModule.bbii', 'Member groups'), 'url' => array('setting/group')),
	array('label' => Yii::t('BbiiModule.bbii', 'Moderators'), 'url' => array('setting/moderator')),
	array('label' => Yii::t('BbiiModule.bbii', 'Webspiders'), 'url' => array('setting/spider')),
);

// @depricated 2.2.0 Load all the needed assets via AppAsset - DJE : 2015-05-15
/*Yii::$app->clientScript->registerScript('confirmation', "
var confirmation = new Array();
confirmation[0] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this category?') . "';
confirmation[1] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this forum?') . "';
", CClientScript::POS_BEGIN);*/
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<div class = "bbii-box-top"><?php echo Yii::t('BbiiModule.bbii', 'Add category or forum'); ?></div>
	
	<div class = "form">

	<?php $form = ActiveForm::begin([
		'enableAjaxValidation' => false,
		'id'                   => 'bbii-forum-form',
	]); ?>
		<?php // @todo Iterate on forms - DJE : 2015-05-15 ?>

		<p class = "note"><?php echo Yii::t('BbiiModule.bbii', 'Fields with <span class = "required">*</span> are required.'); ?></p>
		
		<?php echo $form->errorSummary($model); ?>
		
		<div class = "row">
			<?php echo $form->field($model,'name')->textInput(array('size' => 100,'maxlength' => 255, 'id' => 'name')); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
		
		<div class = "row">
			<?php echo $form->field($model,'subtitle')->textInput(array('size' => 100,'maxlength' => 255, 'id' => 'subtitle')); ?>
			<?php echo $form->error($model,'subtitle'); ?>
		</div>
		
		<div class = "row">
			<?php echo $form->dropDownList($model,'type',array('0' => Yii::t('BbiiModule.bbii', 'Category'),'1' => Yii::t('BbiiModule.bbii', 'Forum')), array('id' => 'type')); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>
		
		<div class = "row">
			<?php echo $form->dropDownList($model,'cat_id',ArrayHelper::map(BbiiForum::find()->categories()->findAll(), 'id', 'name'), array('empty' => '', 'id' => 'cat_id')); ?>
			<?php echo $form->error($model,'cat_id'); ?>
		</div>
		
		<div class = "row buttons">
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Add')); ?>
		</div>
	
	<?php ActiveForm::end(); ?>

	</div><!-- form -->	
	
	<?php /*
	<div class = "bbii-box-top"><?php echo Yii::t('BbiiModule.bbii', 'Forum layout'); ?></div>
	<div class = "sortable">
	<?php
		$items = array();
		foreach($category as $data) {
			$forum = BbiiForum::find()->sorted()->forum()->findAll("cat_id = $data->id");
			$items['cat_'.$data->id] = $this->render('_category', array('data' => $data, 'forum' => $forum), true);
		}
		// @depricated 2.2.0 Kept for referance

		$this->widget('zii.widgets.jui.CJuiSortable', array(
			'id' => 'sortcategory',
			'items' => $items,
			'htmlOptions' => array('style' => 'list-style:none;;margin-top:1px'),
			'theme' => $this->module->juiTheme,
			'options' => array(
				'delay' => '100',
				'update' => 'js:function(){Sort(this,"' . Yii::$app->urlManager->createAbsoluteUrl('setting/ajaxSort') . '");}',
			),
		));
		echo Sortable::widget([
			'id' => 'sortcategory',
			'clientOptions' => ['cursor' => 'move'],
			'itemOptions'   => ['tag' => 'li'],
			'items'         => $items,
			'options'       => array(
				'delay'  => '100',
				'update' => 'js:function(){Sort(this,"' . Yii::$app->urlManager->createAbsoluteUrl('setting/ajaxSort') . '");}',
			),
		]);
	?>
	</div>
</div>

<?php // @depricated 2.2.0 Kept for referance

$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id' => 'dlgEditForum',
	'theme' => $this->module->juiTheme,
    'options' => array(
        'title' => 'Edit',
        'autoOpen' => false,
		'modal' => true,
		'width' => 800,
		'show' => 'fade',
		'buttons' => array(
			Yii::t('BbiiModule.bbii', 'Delete') => 'js:function(){ deleteForum("' . Yii::$app->urlManager->createAbsoluteUrl('setting/deleteForum') .'"); }',
			Yii::t('BbiiModule.bbii', 'Save') => 'js:function(){ saveForum("' . Yii::$app->urlManager->createAbsoluteUrl('setting/saveForum') .'"); }',
			Yii::t('BbiiModule.bbii', 'Cancel') => 'js:function(){ $(this).dialog("close"); }',
		),
    ),
));
echo $this->render('_editForum', array('model' => $model));
$this->endWidget('zii.widgets.jui.CJuiDialog');


Dialog::begin([
    'clientOptions' => [
        'modal' => true,
    ],
]);

echo $this->render('_editForum', array('model' => $model));

Dialog::end();
*/
