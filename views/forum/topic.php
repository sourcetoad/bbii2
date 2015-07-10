<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this ForumController */
/* @var $forum BbiiForum */
/* @var $topic BbiiTopic */
/* @var $dataProvider ActiveDataProvider */
/* @var $postId integer */

// @note Old JS client logic inclustion
/*
Yii::$app->getClientScript()->registerScriptFile(Yii::$app->getClientScript()->getCoreScriptUrl().'/jui/js/jquery-ui-i18n.min.js',CClientScript::POS_END);

Yii::$app->clientScript->registerScript('language', "
	var language = \"" . substr(Yii::$app->language, 0, 2) . "\";", 
CClientScript::POS_BEGIN);

Yii::$app->clientScript->registerScript('scrollToPost', "
	var aTag = $('a[name = \"" . $postId . "\"]');
	if (aTag.length > 0) {
		$('html,body').animate({scrollTop: aTag.offset().top},'fast');
	}
", CClientScript::POS_READY);
*/

/* $this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	$forum->name => array('forum/forum', 'id' => $forum->id),
	$topic->title,
);*/ 

$approvals = BbiiPost::find()->unapproved()->count();
$reports   = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'),							'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 							'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')','url' => array('moderator/approval'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 	' (' . $reports . ')', 	'url' => array('moderator/report'), 	'visible' => $this->context->isModerator()),
);
?>

<?php echo Yii::$app->session->getFlash(); ?>

<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<h3>
        Topic: <?php echo $topic->title; ?>
    </h3>
	
	<?php if (!Yii::$app->user->isGuest && $this->context->module->userMailColumn && $this->context->module->allowTopicSub) { ?>
		<?php if ($this->isWatching($topic->id)) { ?>
			<?php echo Html::button(Yii::t('BbiiModule.bbii', 'Stop watching topic'), array('class' => 'bbii-watch-button','id' => 'unwatch','onclick' => 'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . Yii::$app->urlManager->createAbsoluteUrl('forum/unwatch') . '")')); ?>
			<?php echo Html::button(Yii::t('BbiiModule.bbii', 'Watch topic'), array('style' => 'display:none','class' => 'bbii-watch-button','id' => 'watch','onclick' => 'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . Yii::$app->urlManager->createAbsoluteUrl('forum/watch') . '")')); ?>
		<?php } else { ?>
			<?php echo Html::button(Yii::t('BbiiModule.bbii', 'Stop watching topic'), array('style' => 'display:none','class' => 'bbii-watch-button','id' => 'unwatch','onclick' => 'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . Yii::$app->urlManager->createAbsoluteUrl('forum/unwatch') . '")')); ?>
			<?php echo Html::button(Yii::t('BbiiModule.bbii', 'Watch topic'), array('class' => 'bbii-watch-button','id' => 'watch','onclick' => 'BBii.watchTopic(' . $topic->id . ',' . $topic->last_post_id . ',"' . Yii::$app->urlManager->createAbsoluteUrl('forum/watch') . '")')); ?>
		<?php }; ?>
	<?php }; ?>

	<?php if (!(Yii::$app->user->isGuest || $topic->locked) || $this->context->isModerator()) { ?>
	<div class = "form">
		<?php // @deprecated 2.7.5
		/* $form = $this->beginWidget('ActiveForm', array(
			'action'               => array('forum/reply', 'id' => $topic->id),
			'enableAjaxValidation' => false,
			'id'                   => 'create-post-form',
		)); ?>
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Reply'), array('class' => 'bbii-topic-button')); ?>
		<?php $this->endWidget(); ?>
		*/ ?>
		<?php
		$form = ActiveForm::begin([
			'action'               => array('forum/reply', 'id' => $topic->id),
			'enableAjaxValidation' => false,
			'id'                   => 'create-post-form',
		]);
			echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Reply'), array('class' => 'btn btn-success btn-lg'));
		ActiveForm::end();
		?>
	</div><!-- form -->	
	<?php }; ?>
	
	<?php // @deprecated 2.7.5
	/* $this->widget('zii.widgets.CListView', array(
		'afterAjaxUpdate' => 'function(){$(window).scrollTop(0);}',
		'dataProvider'    => $dataProvider,
		'id'              => 'bbiiPost',
		'itemView'        => '_post',
		'pager'           => array('firstPageCssClass' => 'previous', 'lastPageCssClass' => 'next', 'firstPageLabel' => '<<', 'lastPageLabel' => '>>'),
		'template'        => '{pager}{items}{pager}',
		'viewData'        => array('postId' => $postId),
	));*/ ?>

	<?php echo ListView::widget([
		'dataProvider' => $dataProvider,
		'id'           => 'bbiiTopic',
		'itemView'     => '_post',
	]) ?>
</div>
<div style = "display:none;">
<?php // @deprecated 2.7.5
/*$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'      => 'dlgReportForm',
	'options' => array(
		'modal'    => true,
		'show'     => 'fade',
		'title'    => Yii::t('BbiiModule.bbii', 'Report post'),
		'width'    => 800,
	),
	'theme'   => $this->module->juiTheme,
));

	echo $this->render('_reportForm', array('model' => new BbiiMessage));

$this->endWidget('zii.widgets.jui.CJuiDialog');
*/
?>
</div>
