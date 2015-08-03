<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiMembergroup;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\UrlManager;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	//Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Members'),
);

$approvals = BbiiPost::find()->unapproved()->count();
$reports = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url' => array('moderator/approval'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url' => array('moderator/report'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 'url' => array('moderator/admin'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 'url' => array('moderator/ipadmin'), 'visible' => $this->context->isModerator()),
);
?>
<div id="bbii-wrapper" class="well clearfix">
	<?php echo $this->render('_header', array('item' => $item)); ?>

	<?php // @depricated 2.1.5 Kept for referance
	/* $this->widget('zii.widgets.grid.CGridView', array(
		'id'           => 'member-grid',
		'dataProvider' => $model->search(),
		'filter'       => $model,
		/*'columns' => array(
			array(
				'header' => 'Avatar',
				'type' => 'image',
				'value' => '(isset($data->avatar))?"'.\Yii::$app->request->baseUrl . $this->module->avatarStorage . '/$data->avatar":"'.$assets->baseUrl.'empty.jpeg').'"',
				'htmlOptions' => array('class' => 'img30'),
			),
			array(
				'name' => 'member_name',
				'type' => 'raw',
				'value' => 'Html::a(Html::encode($data->member_name), array("member/view", "id" => $data->id))',
			),
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Joined'),
				'value' => 'DateTimeCalculation::full($data->first_visit)',
			),
			array(
				'header' => Yii::t('BbiiModule.bbii', 'Last visit'),
				'value' => 'DateTimeCalculation::full($data->last_visit)',
			),
			array(
				'name' => 'group_id',
				'filter' => Html::listData(BbiiMembergroup::find()->findAll(), 'id', 'name'),
				'value' => '(isset($data->group))?$data->group->name:""',
			),
		),
	));*/ ?>

  <div class="well clearfix">
    <?= GridView::widget([
		'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
 
			[
				'attribute' => 'member_name',
				'value'     => 'member_name',
			],
			[
				'attribute' => 'first_visit',
				'value'     => 'first_visit',
			],
			[
				'attribute' => 'last_visit',
				'value'     => 'last_visit',
			],
            'group_id',

            [
            	'class' => 'yii\grid\ActionColumn',
            	'template' => '{view} {update}',
            ],

           ],
		'dataProvider' => $model->search(),
		'id'           => 'member-grid',

	]); ?>
    </div>

	<?php echo $this->render('_footer'); ?>

	<div id = "bbii-copyright">
		<a href = "http://www.yiiframework.com/extension/bbii/" target = "_blank" title = "&copy; 2013-<?php echo date('Y'); ?><?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->context->module->version; ?>">BBii forum software</a>
		, <a href = "http://www.sourcetoad.com/" 				target = "_blank" >&copy; <?php echo date('Y'); ?> Sourcetoad, LLC.</a>
	</div>

</div>
