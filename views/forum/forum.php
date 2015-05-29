<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this ForumController */
/* @var $forum BbiiForum */
/* @var $dataProvider ArrayDataProvider */

/* $this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	$forum->name,
); */

$approvals = BbiiPost::find()->unapproved()->count();
$reports   = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 							'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 							'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')','url' => array('moderator/approval'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 	' (' . $reports . ')', 	'url' => array('moderator/report'), 	'visible' => $this->context->isModerator()),
);
?>

<?php echo Yii::$app->session->getFlash(); ?>

<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
	<div class = "forum-category center">
		<div class = "header2">
			<?php echo $forum->name; ?>
		</div>
	</div>
	
	<?php if (!(Yii::$app->user->isGuest || $forum->locked) || $this->context->isModerator()) { ?>
	<div class = "form">
		<?php // @depricated Kept for referance
		/* $form = $this->beginWidget('ActiveForm', array(
			'id' => 'create-topic-form',
			'action' => array('forum/createTopic'),
			'enableAjaxValidation' => false,
		)); ?>
			<?php echo $form->hiddenField($forum, 'id'); ?>
			<?php echo Html::submitButton(Yii::t('BbiiModule.bbii','Create new topic'), array('class' => 'bbii-topic-button')); ?>
		<?php $this->endWidget(); ?>
		*/ ?>
		<?php
		$form = ActiveForm::begin([
				'action'               => array('createtopic'),
				'enableAjaxValidation' => false,
				'id'                   => 'create-topic-form',
		]);
			echo $form->field($forum, 'id')->hiddenInput()->label(false);
			echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Create new topic'), array('class' => 'btn btn-success'));
		ActiveForm::end();
		?>
	</div><!-- form -->	
	<?php }; ?>

	<?php // @depricated 2.7.5 Kept for referance
	/* $this->widget('zii.widgets.CListView', array(
		'id' => 'bbiiTopic',
		'dataProvider' => $dataProvider,
		'itemView' => '_topic',
		'template' => '{pager}{items}{pager}',
		'pager' => array('firstPageCssClass' => 'previous', 'lastPageCssClass' => 'next', 'firstPageLabel' => '<<', 'lastPageLabel' => '>>'),
	));*/ ?>
	<?php echo ListView::widget([
		'dataProvider' => $dataProvider,
		'id'           => 'bbiiTopic',
		'itemView'     => '_topic',
		//'pager'        => array('firstPageCssClass' => 'previous', 'lastPageCssClass' => 'next', 'firstPageLabel' => '<<', 'lastPageLabel' => '>>'),
		//'template'     => '{pager}{items}{pager}',
	]) ?>

	<?php echo $this->render('_forumfooter'); ?>
	<div id = "bbii-copyright">
		<a href = "http://www.yiiframework.com/extension/bbii/" target = "_blank" title = "&copy; 2013-2014 <?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->context->module->version; ?>">BBii forum software</a>
	</div>
</div>


<?php // @depricated 2.7.5 Kept for referance 
/*
<div style = "display:none;">

	if ($this->context->isModerator()) {
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
					Yii::t('BbiiModule.bbii','Change') => 'js:function(){ BBii.changeTopic("' . Yii::$app->urlManager->createAbsoluteUrl('moderator/changeTopic') . '"); }',
					Yii::t('BbiiModule.bbii','Cancel') => 'js:function(){ $(this).dialog("close"); }',
				),
			),
		));

			echo $this->render('_topicForm', array('model' => new BbiiTopic));

		$this->endWidget('zii.widgets.jui.CJuiDialog');
	};
	?>
</div>
*/
?>
