<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this ForumController */
/* @var $dataProvider ArrayDataProvider */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum'),
);

$approvals = BbiiPost::find()->unapproved()->count();
$reports   = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 							'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 							'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')','url' => array('moderator/approval'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 	' (' . $reports . ')', 	'url' => array('moderator/report'), 	'visible' => $this->context->isModerator()),
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>

	<?php // @depricated 2.7.0
	/*$this->widget('zii.widgets.CListView', array(
		'id' => 'bbiiForum',
		'dataProvider' => $dataProvider,
		'itemView' => '_forum',
		'viewData' => array('lastIndex' => ($dataProvider->totalItemCount - 1)),
		'summaryText' => false,
	));*/ ?>

	<?php echo ListView::widget([
			'dataProvider' => $dataProvider,
			'id'           => 'bbiiForum',
			'itemOptions'  => ['class' => 'item'],
			'itemView'     => '_forum',
			'summary'      => false, 
	]) ?>

	<?php echo $this->render('_footer'); ?>
	<?php if (!Yii::$app->user->isGuest) {
		echo Html::a(
			Yii::t('BbiiModule.bbii','Mark all read'),
			array('forum/markallread'),
			['class' => 'btn btn-success']
		);
	} ?>
	<div id = "bbii-copyright">
		<a href = "http://www.yiiframework.com/extension/bbii/" target = "_blank" title = "&copy; 2013-<?php echo date('Y'); ?><?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->context->module->version; ?>">BBii forum software</a>
		, <a href = "http://www.sourcetoad.com/" 				target = "_blank" >&copy; <?php echo date('Y'); ?> Sourcetoad, LLC.</a>
	</div>
</div>
