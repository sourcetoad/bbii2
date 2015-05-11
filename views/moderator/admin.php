<?php

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiPost */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Posts'),
);

$approvals = BbiiPost::model()->unapproved()->count();
$reports = BbiiMessage::model()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url' => array('moderator/approval'), 'visible' => $this->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url' => array('moderator/report'), 'visible' => $this->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 'url' => array('moderator/admin'), 'visible' => $this->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 'url' => array('moderator/ipadmin'), 'visible' => $this->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 'url' => array('moderator/sendmail'), 'visible' => $this->isModerator()),
);

Yii::$app->clientScript->registerScript('setAutocomplete', "
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

?>

<div id="bbii-wrapper">
	<?= $this->renderPartial('_header', array('item' => $item)); ?>

	<?php 
	$dataProvider = $model->search();
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
				'filter' => Html::listData(BbiiForum::getAllForumOptions(), 'id', 'name', 'group'),
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
				'class' => 'CButtonColumn',
				'template' => '{view}{update}{delete}',
				'buttons' => array(
					'view' => array(
						'url' => 'array("forum/topic", "id" => $data->topic_id, "nav" => $data->id)',
						'imageUrl' => $assets->baseUrl.'/images/view.png',
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
	)); ?>
</div>