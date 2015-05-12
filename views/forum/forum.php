<?php
/* @var $this ForumController */
/* @var $forum BbiiForum */
/* @var $dataProvider CArrayDataProvider */

/*
$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	$forum->name,
);
*/

$approvals = BbiiPost::find()->unapproved()->count();
$reports = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url' => array('moderator/approval'), 'visible' => $this->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url' => array('moderator/report'), 'visible' => $this->isModerator()),
);
?>

<?php if(Yii::$app->user->hasFlash('moderation')): ?>
<div class="flash-notice">
	<?= Yii::$app->user->getFlash('moderation'); ?>
</div>
<?php endif; ?>

<div id="bbii-wrapper">
	<?= $this->render('_header', array('item' => $item)); ?>
	
	<div class="forum-category center">
		<div class="header2">
			<?= $forum->name; ?>
		</div>
	</div>
	
	<?php if(!(Yii::$app->user->isGuest || $forum->locked) || $this->isModerator()): ?>
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'create-topic-form',
			'action' => array('forum/createTopic'),
			'enableAjaxValidation' => false,
		)); ?>
			<?= $form->hiddenField($forum, 'id'); ?>
			<?= Html::submitButton(Yii::t('BbiiModule.bbii','Create new topic'), array('class' => 'bbii-topic-button')); ?>
		<?php $this->endWidget(); ?>
	</div><!-- form -->	
	<?php endif; ?>

	<?php $this->widget('zii.widgets.CListView', array(
		'id' => 'bbiiTopic',
		'dataProvider' => $dataProvider,
		'itemView' => '_topic',
		'template' => '{pager}{items}{pager}',
		'pager' => array('firstPageCssClass' => 'previous', 'lastPageCssClass' => 'next', 'firstPageLabel' => '<<', 'lastPageLabel' => '>>'),
	)); ?>
	
	<?= $this->render('_forumfooter'); ?>
	<div id="bbii-copyright"><a href="http://www.yiiframework.com/extension/bbii/" target="_blank" title="&copy; 2013-2014 <?= Yii::t('BbiiModule.bbii','version') . ' ' . $this->module->version; ?>">BBii forum software</a></div>
</div>
<div style="display:none;">
<?php 
if($this->isModerator()) {
	$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
		'id' => 'dlgTopicForm',
		'theme' => $this->module->juiTheme,
		'options' => array(
			'title' => Yii::t('BbiiModule.bbii', 'Update topic'),
			'autoOpen' => false,
			'modal' => true,
			'width' => 800,
			'show' => 'fade',
			'buttons' => array(
				Yii::t('BbiiModule.bbii','Change') => 'js:function(){ BBii.changeTopic("' . $this->createAbsoluteUrl('moderator/changeTopic') . '"); }',
				Yii::t('BbiiModule.bbii','Cancel') => 'js:function(){ $(this).dialog("close"); }',
			),
		),
	));

		echo $this->render('_topicForm', array('model' => new BbiiTopic));

	$this->endWidget('zii.widgets.jui.CJuiDialog');
}
?>
</div>