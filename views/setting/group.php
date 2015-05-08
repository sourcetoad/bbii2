<?php
/* @var $this SettingController */
/* @var $model BbiiMembergroup */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings')=>array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Member groups')
);

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Settings'), 'url'=>array('setting/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum layout'), 'url'=>array('setting/layout')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Member groups'), 'url'=>array('setting/group')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Moderators'), 'url'=>array('setting/moderator')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Webspiders'), 'url'=>array('setting/spider')),
);

Yii::$app->clientScript->registerScript('confirmation', "
var confirmation = '" . Yii::t('BbiiModule.bbii', 'Are you sure that you want to delete this member group?') . "'
", CClientScript::POS_BEGIN);
?>
<div id="bbii-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<?php echo Html::button(Yii::t('BbiiModule.bbii', 'New group'), array('onclick'=>'editMembergroup()', 'class'=>'down35')); ?>
	
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'membergroup-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>array(
			array(
				'name' => 'id',
	//			'visible'=>false,
			),
			'name',
			'description',
			'min_posts',
			array(
				'name' => 'color',
				'type' => 'raw',
				'value' => '"<span style=\"font-weight:bold;color:#$data->color\">$data->color</span>"',
			),
			'image',
			array(
				'class'=>'CButtonColumn',
				'template'=>'{update}',
				'buttons' => array(
					'update' => array(
						'click'=>'js:function($data) { editMembergroup($(this).closest("tr").children("td:first").text(), "' . $this->createAbsoluteUrl('setting/getMembergroup') .'");return false; }',
					),
				)
			),
		),
	)); ?>
</div>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'dlgEditMembergroup',
	'theme'=>$this->module->juiTheme,
    'options'=>array(
        'title'=>'Edit',
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>450,
		'show'=>'fade',
		'buttons'=>array(
			Yii::t('BbiiModule.bbii', 'Delete')=>'js:function(){ deleteMembergroup("' . $this->createAbsoluteUrl('setting/deleteMembergroup') .'"); }',
			Yii::t('BbiiModule.bbii', 'Save')=>'js:function(){ saveMembergroup("' . $this->createAbsoluteUrl('setting/saveMembergroup') .'"); }',
			Yii::t('BbiiModule.bbii', 'Cancel')=>'js:function(){ $(this).dialog("close"); }',
		),
    ),
));

    echo $this->renderPartial('_editMembergroup', array('model'=>$model));

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>