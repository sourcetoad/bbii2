<?php
/* @var $this ForumController */
/* @var $model BbiiSetting */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings')=>array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Moderators')
);

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Settings'), 'url'=>array('setting/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum layout'), 'url'=>array('setting/layout')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Member groups'), 'url'=>array('setting/group')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Moderators'), 'url'=>array('setting/moderator')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Webspiders'), 'url'=>array('setting/spider')),
);
?>
<div id="bbii-wrapper">
	<?= $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'bbii-member-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'rowCssClassExpression'=>'(Yii::$app->authManager && Yii::$app->authManager->checkAccess("moderator", $data->id))?"moderator":(($row % 2)?"even":"odd")',
		'columns'=>array(
			'member_name',
			array(
				'name'=>'group_id',
				'value'=>'$data->group->name',
				'filter'=>Html::listData(BbiiMembergroup::model()->findAll(), 'id', 'name'),
			),
			array(
				'name'=>'moderator',
				'value'=>'Html::checkBox("moderator", $data->moderator, array("onclick"=>"changeModeration(this,$data->id,\'' . $this->createAbsoluteUrl('setting/changeModerator') . '\')"))',
				'type'=>'raw',
				'filter'=>array('0'=>Yii::t('BbiiModule.bbii', 'No'), '1'=>Yii::t('BbiiModule.bbii', 'Yes')),
				'htmlOptions'=>array("style"=>"text-align:center"),
			),
			
		),
	)); ?>
</div>