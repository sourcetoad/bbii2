<?php

use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiTopic;

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $item array */

$approvals = BbiiPost::find()->unapproved()->count();
$reports   = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 							'url' => array('forum/index')),
	//array('label' => Yii::t('BbiiModule.bbii', 'Members'), 							'url' => array('member/index')),
	//array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')','url' => array('moderator/approval'), 	'visible' => $this->context->isModerator()),
	//array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 	' (' . $reports . ')', 	'url' => array('moderator/report'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 							'url' => array('moderator/admin'), 		'visible' => $this->context->isModerator()),
	//array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 						'url' => array('moderator/ipadmin'), 	'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 						'url' => array('moderator/sendmail'), 	'visible' => $this->context->isModerator()),
);
?>
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
			echo Html::a(Html::img($assets->baseUrl.'/images/moderator.png', array('title' => Yii::t('BbiiModule.bbii', 'Moderate'), 'style' => 'vertical-align:bottom;')), array('moderator/approval'),array('class'=>'btn btn-default'));
			echo Html::a(Html::img($assets->baseUrl.'/images/config.png', array('title' => Yii::t('BbiiModule.bbii', 'Forum settings'), 'style' => 'vertical-align:bottom;')), array('setting/index'),array('class'=>'btn btn-default'));
		?>
		</div>
	<?php endif; ?>
	<h2><?php echo $this->context->module->forumTitle; ?></h2>
    <br />
    <div id = "nav" class="clearfix">
    <?php  echo Nav::widget([
        'items' => $item,
        'options' => array('class'=>'nav nav-pills')
    ]); ?>
    </div>
</div>
<?php /*if (isset($this->context->bbii_breadcrumbs)):?>
	<?php echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif*/?>
