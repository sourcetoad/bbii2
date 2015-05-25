<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Settings'), 	'url' => array('setting/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Forum layout'), 'url' => array('setting/layout')),
	array('label' => Yii::t('BbiiModule.bbii', 'Member groups'),'url' => array('setting/group')),
	array('label' => Yii::t('BbiiModule.bbii', 'Moderators'), 	'url' => array('setting/moderator')),
	array('label' => Yii::t('BbiiModule.bbii', 'Webspiders'), 	'url' => array('setting/spider')),
);

/* @var $this ForumController */
/* @var $item array */
?>
<div id = "bbii-header">
	<?php if (!Yii::$app->user->isGuest): ?>
		<div class = "bbii-profile-box">
		<?php echo Html::a(Yii::t('BbiiModule.bbii', 'Forum'), array('forum/index')); ?>
		</div>
	<?php endif; ?>
	<div class = "bbii-title"><?php echo Yii::t('BbiiModule.bbii', 'Forum settings'); ?></div>
	<table style = "margin:0;"><tr><td style = "padding:0;">
		<div id = "bbii-menu">
		<?php  echo Nav::widget([
		    'items' => $item,
		]); ?>
		</div>
	</td></tr></table>
</div>
<?php /*if (isset($this->context->bbii_breadcrumbs)):?>
	<?php echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif*/?>