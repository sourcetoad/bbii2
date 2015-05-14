<?php

use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiMessage;

use yii\helpers\Html;
use yii\bootstrap\Nav;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $item array */
?>
<div id = "bbii-header">
	<?php if(!Yii::$app->user->isGuest): ?>
		<?php $messages = BbiiMessage::find()->inbox()->unread()->count('sendto = '.Yii::$app->user->id); ?>
		<div class = "bbii-profile-box">
		<?php 
			if($messages) {
				echo Html::a(Html::img($asset->baseUrl.'newmail.png', Yii::t('BbiiModule.bbii', 'new messages'), array('title' => $messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages'),'style' => 'vertical-align:bottom;')), array('message/inbox')); 
			} else {
				echo Html::a(Html::img($asset->baseUrl.'mail.png', Yii::t('BbiiModule.bbii', 'no new messages'), array('title' => Yii::t('BbiiModule.bbii', 'no new messages'),'style' => 'vertical-align:bottom;')), array('message/inbox')); 
			}
			echo ' | ' . Html::a(Html::img($asset->baseUrl.'settings.png', Yii::t('BbiiModule.bbii', 'My settings'), array('title' => Yii::t('BbiiModule.bbii', 'My settings'),'style' => 'vertical-align:bottom;')), array('member/view', 'id'  => Yii::$app->user->id)); 
			if($this->context->isModerator()) echo ' | ' . Html::a(Html::img($asset->baseUrl.'moderator.png', Yii::t('BbiiModule.bbii', 'Moderate'), array('title' => Yii::t('BbiiModule.bbii', 'Moderate'),'style' => 'vertical-align:bottom;')), array('moderator/approval'));
			if($this->context->isAdmin()) echo ' | ' . Html::a(Html::img($asset->baseUrl.'config.png', Yii::t('BbiiModule.bbii', 'Forum settings'), array('title' => Yii::t('BbiiModule.bbii', 'Forum settings'),'style' => 'vertical-align:bottom;')), array('setting/index'));
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
	</td><td style = "padding:0;text-align:right;vertical-align:top;">
		<div class = "search">
			<?php //$this->widget('SimpleSearchForm'); ?>
		</div>
	</td></tr></table>
</div>
<?php /*echo Html::dropDownList('bbii-jumpto', '', Html::listData(BbiiForum::getForumOptions(), 'id', 'name', 'group'), 
	array('empty' => Yii::t('BbiiModule.bbii','Select forum'),
	'onchange' => "window.location.href = '" . Html::normalizeUrl(array('forum')) . "/id/'+$(this).val()",
));*/ ?>
<?php if(isset($this->bbii_breadcrumbs)):?>
	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'homeLink' => false,
		'links' => $this->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif?>
