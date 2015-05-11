<?php
/* @var $this SettingController */
/* @var $model BbiiForum */
/* @var $category[] BbiiForum  */

$this->bbii_breadcrumbs=array(
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

Yii::$app->clientScript->registerScript('confirmation', "
var confirmation = new Array();
confirmation[0] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this category?') . "';
confirmation[1] = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this forum?') . "';
", CClientScript::POS_BEGIN);
?>
<div id="bbii-wrapper">
	<?= $this->renderPartial('_header', array('item' => $item)); ?>
	
	<div class="bbii-box-top"><?= Yii::t('BbiiModule.bbii', 'Add category or forum'); ?></div>
	
	<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'bbii-forum-form',
		'enableAjaxValidation' => false,
	)); ?>

		<p class="note"><?= Yii::t('BbiiModule.bbii', 'Fields with <span class="required">*</span> are required.'); ?></p>
		
		<?= $form->errorSummary($model); ?>
		
		<div class="row">
			<?= $form->labelEx($model,'name'); ?>
			<?= $form->textField($model,'name',array('size' => 100,'maxlength' => 255, 'id' => 'name')); ?>
			<?= $form->error($model,'name'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'subtitle'); ?>
			<?= $form->textField($model,'subtitle',array('size' => 100,'maxlength' => 255, 'id' => 'subtitle')); ?>
			<?= $form->error($model,'subtitle'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'type'); ?>
			<?= $form->dropDownList($model,'type',array('0' => Yii::t('BbiiModule.bbii', 'Category'),'1' => Yii::t('BbiiModule.bbii', 'Forum')), array('id' => 'type')); ?>
			<?= $form->error($model,'type'); ?>
		</div>
		
		<div class="row">
			<?= $form->labelEx($model,'cat_id'); ?>
			<?= $form->dropDownList($model,'cat_id',Html::listData(BbiiForum::find()->categories()->findAll(), 'id', 'name'), array('empty' => '', 'id' => 'cat_id')); ?>
			<?= $form->error($model,'cat_id'); ?>
		</div>
		
		<div class="row buttons">
			<?= Html::submitButton(Yii::t('BbiiModule.bbii', 'Add')); ?>
		</div>
		
	<?php $this->endWidget(); ?>
	
	</div><!-- form -->	
	
	
	<div class="bbii-box-top"><?= Yii::t('BbiiModule.bbii', 'Forum layout'); ?></div>
	<div class="sortable">
	<?php
		$items = array();
		foreach($category as $data) {
			$forum = BbiiForum::find()->sorted()->forum()->findAll("cat_id = $data->id");
			$items['cat_'.$data->id] = $this->renderPartial('_category', array('data' => $data, 'forum' => $forum), true);
		}
		$this->widget('zii.widgets.jui.CJuiSortable', array(
			'id' => 'sortcategory',
			'items' => $items,
			'htmlOptions' => array('style' => 'list-style:none;;margin-top:1px'),
			'theme' => $this->module->juiTheme,
			'options' => array(
				'delay' => '100',
				'update' => 'js:function(){Sort(this,"' . $this->createAbsoluteUrl('setting/ajaxSort') . '");}',
			),
		));
	?>
	</div>
</div>

<?php
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
			Yii::t('BbiiModule.bbii', 'Delete') => 'js:function(){ deleteForum("' . $this->createAbsoluteUrl('setting/deleteForum') .'"); }',
			Yii::t('BbiiModule.bbii', 'Save') => 'js:function(){ saveForum("' . $this->createAbsoluteUrl('setting/saveForum') .'"); }',
			Yii::t('BbiiModule.bbii', 'Cancel') => 'js:function(){ $(this).dialog("close"); }',
		),
    ),
));

    echo $this->renderPartial('_editForum', array('model' => $model));

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>