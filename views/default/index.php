<?php

/* @var $this ForumController */
/* @var $dataProvider CArrayDataProvider */

/*$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum'),
);*/

$approvals = $BbiiPost->find()->All();   //->model()->unapproved()->count();
$reports   = $BbiiMessage->find()->All();//->model()->report()->count();

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum'), 'url'=>array('forum/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Members'), 'url'=>array('member/index')),
	//array('label'=>Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url'=>array('moderator/approval'), 'visible'=>$this->isModerator()),
	//array('label'=>Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url'=>array('moderator/report'), 'visible'=>$this->isModerator()),
);
?>
<div id="bbii-wrapper">
	<?php echo $this->render('_header', array('BbiiMessage' => $BbiiMessage, 'item' => $item)); ?>


	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider' => $dataProvider,
		'id'           => 'bbiiForum',
		'itemView'     => '_forum',
		'summaryText'  => false,
		'viewData'     => array('lastIndex'=>($dataProvider->totalItemCount - 1)),
	)); ?>
	
	<?php echo $this->renderPartial('_footer'); ?>
	<?php if(!Yii::$app->user->isGuest) echo Html::a(Yii::t('BbiiModule.bbii','Mark all read'), array('forum/markAllRead')); ?>
	<div id="bbii-copyright"><a href="http://www.yiiframework.com/extension/bbii/" target="_blank" title="&copy; 2013-<?php echo date('Y'); ?> <?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->module->version; ?>">BBii forum software</a></div>
</div>