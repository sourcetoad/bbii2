<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this ForumController */
/* @var $item array */
?>
<div id="bbii-header">
	<?php if(!Yii::$app->user->isGuest): ?>
		<div class="bbii-profile-box">
		<?php echo Html::a(Yii::t('BbiiModule.bbii', 'Forum'), array('forum/index')); ?>
		</div>
	<?php endif; ?>
	<div class="bbii-title"><?php echo Yii::t('BbiiModule.bbii', 'Forum settings'); ?></div>
	<table style="margin:0;"><tr><td style="padding:0;">
		<div id="bbii-menu">
		<?php /*
		$this->widget('zii.widgets.CMenu',array(
			'items' => array(
				array('label' => Yii::t('BbiiModule.bbii', 'Settings'), 	'url' => array('setting/index')),
				array('label' => Yii::t('BbiiModule.bbii', 'Forum layout'), 'url' => array('setting/layout')),
				array('label' => Yii::t('BbiiModule.bbii', 'Member groups'),'url' => array('setting/group')),
				array('label' => Yii::t('BbiiModule.bbii', 'Moderators'), 	'url' => array('setting/moderator')),
				array('label' => Yii::t('BbiiModule.bbii', 'Webspiders'), 	'url' => array('setting/spider')),
			)
		));
		*/ ?>
		</div>
	</td></tr></table>
</div>
<?php /*
if(isset($this->bbii_breadcrumbs)):?>
	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'homeLink' => false,
		'links' => $this->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif
*/ ?>