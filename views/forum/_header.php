<?php

use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiMessage;

use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $item array */
?>

<?php
echo Html::dropDownList(
	'bbii-jumpto',
	null,
	ArrayHelper::map(BbiiForum::getForumOptions(), 'id', 'name'),
	array(
		'prompt' => 'Jump to a Forum',
		'class'    => 'form-control',
		//'empty'    => Yii::t('BbiiModule.bbii', 'Select forum'),
		'onchange' => "window.location.href = '" . \Yii::$app->urlManager->createAbsoluteUrl(array('forum')) . "/forum/forum?id='+$(this).val()",
	)
); ?>

<br />

<div id = "bbii-header">
	<?php if (!\Yii::$app->user->isGuest): ?>
		<?php $messages = BbiiMessage::find()->inbox()->unread()->count('sendto = '.\Yii::$app->user->identity->id ); ?>
		<div class = "btn-group pull-right">
		<?php 
			if ($messages) {
				echo Html::a(Html::img($assets->baseUrl.'/images/newmail.png', array('title' => $messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages'), 'style' => 'vertical-align:bottom;')), array('message/inbox'),array('class'=>'btn btn-default'));
			} else {
				echo Html::a(Html::img($assets->baseUrl.'/images/mail.png', array('title' => Yii::t('BbiiModule.bbii', 'no new messages'), 'style' => 'vertical-align:bottom;')), array('message/inbox'),array('class'=>'btn btn-default'));
			}
			echo Html::a(Html::img($assets->baseUrl.'/images/settings.png', array('title' => Yii::t('BbiiModule.bbii', 'My settings'), 'style' => 'vertical-align:bottom;')), array('member/view', 'id'  => \Yii::$app->user->identity->id ),array('class'=>'btn btn-default'));
			
			if ($this->context->isModerator()) {
				echo Html::a(Html::img($assets->baseUrl.'/images/moderator.png', array('title' => Yii::t('BbiiModule.bbii', 'Moderate'), 'style' => 'vertical-align:bottom;')), array('moderator/approval'),array('class'=>'btn btn-default'));
				echo Html::a(Html::img($assets->baseUrl.'/images/config.png', array('title' => Yii::t('BbiiModule.bbii', 'Forum settings'), 'style' => 'vertical-align:bottom;')), array('setting/index'),array('class'=>'btn btn-default'));
			}
			?>
		</div>
	<?php endif; ?>
	<h2><?php echo $this->context->module->forumTitle; ?></h2>
	<table style = "margin:0;"><tr><td style = "padding:0;">
		<div id = "nav" class="clearfix">
		<?php // @todo Add this feature back - DJE : 2015-05-26 
		/*echo Nav::widget([
		    'items' => $item,
              'options' => array('class'=>'nav nav-pills')
		]);*/ ?>
		</div>
	</td><td style = "padding:0;text-align:right;vertical-align:top;">
		<div class = "search">
			<?php // @todo Add this feature back - DJE : 2015-05-14 
				//$this->widget('SimpleSearchForm');
			?>
		</div>
	</td></tr></table>
</div>

<?php // @todo Breadcrumb disabled for initial release - DJE : 2015-05-28
/* if (isset($this->context->bbii_breadcrumbs)):?>
	<?php echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif */ ?>

<?php
foreach (\Yii::$app->session->getAllFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
\Yii::$app->session->removeAllFlashes();
?>

<noscript>
	<div class = "flash-notice">
	<?php echo Yii::t('BbiiModule.bbii', 'Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
	</div>
</noscript>
