<?php

use frontend\modules\bbii\models\BbiiMembergroup;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\UrlManager;

/* @var $this ForumController */
/* @var $model BbiiSetting */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Settings') => array('setting/index'),
	Yii::t('BbiiModule.bbii', 'Moderators')
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('template/_header', array('item' => $item)); ?>

	<?php // @depricated 2.3.0 Kept for referance
	/* $this->widget('zii.widgets.grid.CGridView', array(
		'id' => 'bbii-member-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'rowCssClassExpression' => '(Yii::$app->authManager && Yii::$app->authManager->checkAccess("moderator", $data->id))?"moderator":(($row % 2)?"even":"odd")',
		'columns' => array(
			'member_name',
			array(
				'name' => 'group_id',
				'value' => '$data->group->name',
				'filter' => ArrayHelper::map(BbiiMembergroup::find()->findAll(), 'id', 'name'),
			),
			array(
				'name' => 'moderator',
				'value' => 'Html::checkBox("moderator", $data->moderator, array("onclick" => "changeModeration(this,$data->id,\'' . Yii::$app->urlManager->createAbsoluteUrl('setting/changeModerator') . '\')"))',
				'type' => 'raw',
				'filter' => array('0' => Yii::t('BbiiModule.bbii', 'No'), '1' => Yii::t('BbiiModule.bbii', 'Yes')),
				'htmlOptions' => array("style" => "text-align:center"),
			),
			
		),
	));*/ ?>

    <p>
        <?php // This is done via the web app itself - DJE : 2015-05-25
        //echo Html::a('Create Moderator', ['../member/create'], ['class' => 'btn btn-success']);
        ?>
    </p>

	<?php echo GridView::widget(array(
		'columns'      => array(
			'member_name',
			array(
				'attribute' => 'group_id',
				'filter'    => ArrayHelper::map(BbiiMembergroup::find()->findAll(), 'id', 'name'),
				'value'     => function ($data) { return (isset($data->group)) ? $data->group->name : null ; },
			),
			array(
				'attribute' => 'moderator',
				'filter'    => array('0' => Yii::t('BbiiModule.bbii', 'No'), '1' => Yii::t('BbiiModule.bbii', 'Yes')),
				'format'    => 'raw',
				'options'   => array("style" => "text-align:center"),
				//'value'     => 'Html::checkBox("moderator", $data->moderator, array("onclick" => "changeModeration(this,$data->id,\'' . Yii::$app->urlManager->createAbsoluteUrl('setting/changeModerator') . '\')"))',
			),
			
			[
				'buttons'=>[
					'update' => function ($url, $model) {     
						return Html::a(
							'<span class="glyphicon glyphicon-pencil"></span>',
							'../member/update?id='.$model->id,
							['title' => Yii::t('yii', 'Update')]
						);                                
					}
				],
				'class'    => 'yii\grid\ActionColumn',
				'template' => '{update}'
			],
		),
		'dataProvider' => $model,
		'id'           => 'moderator-grid',
	)); ?>

</div>