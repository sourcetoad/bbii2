<?php

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiPost */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Posts'),
);

$approvals = BbiiPost::find()->unapproved()->count();
$reports = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url' => array('moderator/approval'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url' => array('moderator/report'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 'url' => array('moderator/admin'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 'url' => array('moderator/ipadmin'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 'url' => array('moderator/sendmail'), 'visible' => $this->context->isModerator()),
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

<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>

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
						'imageUrl' => $asset->baseUrl.'view.png'),
					),
					'update' => array(
						'url' => 'array("forum/update", "id" => $data->id)',
						'label' => Yii::t('BbiiModule.bbii','Update'),
						'imageUrl' => $asset->baseUrl.'update.png'),
						'options' => array('style' => 'margin-left:5px;'),
					),
					'delete' => array(
						'imageUrl' => $asset->baseUrl.'delete.png'),
						'options' => array('style' => 'margin-left:5px;'),
					),
				)
			),
		),
	)); ?>
</div>