<?php

use frontend\modules\bbii\models\BbiiMessage;

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $item array */
/* @var $breadcrumbs array */
?>
<div id = "bbii-header">
	<?php if (!Yii::$app->user->isGuest): ?>
	<?php $messages = BbiiMessage::find()->inbox()->unread()->count('sendto = '.Yii::$app->user->id); ?>
		<div class = "bbii-profile-box">
		<?php 
			if ($messages) {
				echo Html::a(Html::img($assets->baseUrl.'/images/newmail.png', array('title' => $messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages'), 'style' => 'vertical-align:bottom;')), array('message/inbox')); 
			} else {
				echo Html::a(Html::img($assets->baseUrl.'/images/mail.png', array('title' => Yii::t('BbiiModule.bbii', 'no new messages'), 'style' => 'vertical-align:bottom;')), array('message/inbox')); 
			}
			echo ' | '; echo Html::a(Html::img($assets->baseUrl.'/images/settings.png', array('title' => Yii::t('BbiiModule.bbii', 'My settings'), 'style' => 'vertical-align:bottom;')), array('member/view', 'id'  => Yii::$app->user->id)); 
		?>
		</div>
	<?php endif; ?>
	<div class = "bbii-title"><?php echo $this->context->module->forumTitle; ?></div>
	<table style = "margin:0;"><tr><td style = "padding:0;">
		<div id = "bbii-menu">
		<?php  echo Nav::widget([
		    'items' => $item,
		]); ?>
		</div>
	</td></tr></table>
</div>
<?php /* if (isset($this->context->bbii_breadcrumbs)):?>
	<?php echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif*/ ?>

<?php
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
Yii::$app->session->removeFlash();
?>

<noscript>
	<div class = "flash-notice">
	<?php echo Yii::t('BbiiModule.bbii', 'Your web browser does not support JavaScript, or you have temporarily disabled scripting. This site needs JavaScript to function correct.'); ?>
	</div>
</noscript>