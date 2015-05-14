<?php
/* @var $this ForumController */
/* @var $dataProvider ArrayDataProvider */

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum'),
);

$approvals = BbiiPost::find()->unapproved()->count();
$reports = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url' => array('moderator/approval'), 'visible' => $this->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url' => array('moderator/report'), 'visible' => $this->isModerator()),
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>

	<?php $this->widget('zii.widgets.CListView', array(
		'id' => 'bbiiForum',
		'dataProvider' => $dataProvider,
		'itemView' => '_forum',
		'viewData' => array('lastIndex' => ($dataProvider->totalItemCount - 1)),
		'summaryText' => false,
	)); ?>
	
	<?php echo $this->render('_footer'); ?>
	<?php if(!Yii::$app->user->isGuest) echo CHtml::link(Yii::t('BbiiModule.bbii','Mark all read'), array('forum/markAllRead')); ?>
	<div id = "bbii-copyright"><a href = "http://www.yiiframework.com/extension/bbii/" target = "_blank" title = "&copy; 2013-<?php echo date('Y'); ?> <?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->module->version; ?>">BBii forum software</a></div>
</div>