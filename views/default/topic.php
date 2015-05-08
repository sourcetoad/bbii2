<?php
/* @var $this ForumController */
/* @var $forum BbiiForum */
/* @var $topic BbiiTopic */
/* @var $dataProvider ActiveDataProvider */
/* @var $postId integer */
Yii::$app->getClientScript()->registerScriptFile(Yii::$app->getClientScript()->getCoreScriptUrl().'/jui/js/jquery-ui-i18n.min.js',CClientScript::POS_END);
$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	$forum->name => array('forum/forum', 'id'=>$forum->id),
	$topic->title,
);

$approvals = BbiiPost::model()->unapproved()->count();
$reports = BbiiMessage::model()->report()->count();

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum'), 'url'=>array('forum/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Members'), 'url'=>array('member/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url'=>array('moderator/approval'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url'=>array('moderator/report'), 'visible'=>$this->isModerator()),
);

Yii::$app->clientScript->registerScript('language', "
	var language = \"" . substr(Yii::$app->language, 0, 2) . "\";", 
CClientScript::POS_BEGIN);

Yii::$app->clientScript->registerScript('scrollToPost', "
	var aTag = $('a[name=\"" . $postId . "\"]');
	if(aTag.length > 0) {
		$('html,body').animate({scrollTop: aTag.offset().top},'fast');
	}
", CClientScript::POS_READY);

?>

<?php if(Yii::$app->user->hasFlash('moderation')): ?>
<div class="flash-notice">
	<?php echo Yii::$app->user->getFlash('moderation'); ?>
</div>
<?php endif; ?>

<div id="bbii-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<div class="forum-category center">
		<div class="header2">
			<?php echo $topic->title; ?>
		</div>
	</div>
	
	<?php if(!Yii::$app->user->isGuest && $this->module->userMailColumn && $this->module->allowTopicSub): ?>
		<?php if($this->isWatching($topic->id)): ?>
			<?php echo CHtml::button(Yii::t('BbiiModule.bbii', 'Stop watching topic'), array('class'=>'bbii-watch-button','id'=>'unwatch','onclick'=>'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . $this->createAbsoluteUrl('forum/unwatch') . '")')); ?>
			<?php echo CHtml::button(Yii::t('BbiiModule.bbii', 'Watch topic'), array('style'=>'display:none','class'=>'bbii-watch-button','id'=>'watch','onclick'=>'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . $this->createAbsoluteUrl('forum/watch') . '")')); ?>
		<?php else: ?>
			<?php echo CHtml::button(Yii::t('BbiiModule.bbii', 'Stop watching topic'), array('style'=>'display:none','class'=>'bbii-watch-button','id'=>'unwatch','onclick'=>'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . $this->createAbsoluteUrl('forum/unwatch') . '")')); ?>
			<?php echo CHtml::button(Yii::t('BbiiModule.bbii', 'Watch topic'), array('class'=>'bbii-watch-button','id'=>'watch','onclick'=>'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . $this->createAbsoluteUrl('forum/watch') . '")')); ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if(!(Yii::$app->user->isGuest || $topic->locked) || $this->isModerator()): ?>
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'create-post-form',
			'action'=>array('forum/reply', 'id'=>$topic->id),
			'enableAjaxValidation'=>false,
		)); ?>
			<?php echo CHtml::submitButton(Yii::t('BbiiModule.bbii','Reply'), array('class'=>'bbii-topic-button')); ?>
		<?php $this->endWidget(); ?>
	</div><!-- form -->	
	<?php endif; ?>
	
	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'bbiiPost',
		'dataProvider'=>$dataProvider,
		'itemView'=>'_post',
		'viewData'=>array('postId'=>$postId),
		'template'=>'{pager}{items}{pager}',
		'pager'=>array('firstPageCssClass'=>'previous', 'lastPageCssClass'=>'next', 'firstPageLabel'=>'<<', 'lastPageLabel'=>'>>'),
		'afterAjaxUpdate'=>'function(){$(window).scrollTop(0);}',
	)); ?>
</div>
<div style="display:none;">
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'dlgReportForm',
	'theme'=>$this->module->juiTheme,
    'options'=>array(
        'title'=>Yii::t('BbiiModule.bbii', 'Report post'),
        'autoOpen'=>false,
		'modal'=>true,
		'width'=>800,
		'show'=>'fade',
		'buttons'=>array(
			Yii::t('BbiiModule.bbii','Send')=>'js:function(){ sendReport(); }',
			Yii::t('BbiiModule.bbii','Cancel')=>'js:function(){ $(this).dialog("close"); }',
		),
    ),
));

	echo $this->renderPartial('_reportForm', array('model'=>new BbiiMessage));

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
</div>