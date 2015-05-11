<?php

use frontend\modules\bbii\models\BbiiMessage;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $item array */

?>
<div id="bbii-header">
	<?php
	if (!Yii::$app->user->isGuest) {
	
		$messages = BbiiMessage::find()->inbox()->unread()->count('sendto = '.Yii::$app->user->id);
		
		echo '<div class="bbii-profile-box">';
			if ($messages) {
				echo Html::a(
					Html::img(
						$assets->baseUrl.'/images/newmail.png',
						array('title' => $messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages'),
							'style' => 'vertical-align:bottom;')),
					array('message/inbox')
				); 
			} else {
				echo Html::a(
					Html::img($assets->baseUrl.'/images/mail.png',
						array('title' => Yii::t('BbiiModule.bbii', 'no new messages'),
							'style' => 'vertical-align:bottom;')),
					array('message/inbox')
				); 
			}
			echo ' | ';
			echo Html::a(
				Html::img($assets->baseUrl.'/images/settings.png',
					array('title' => Yii::t('BbiiModule.bbii', 'My settings'),
						'style' => 'vertical-align:bottom;')),
				array('member/view', 'id'  => Yii::$app->user->id)
			); 

			//if($this->isModerator()) echo ' | ' . Html::a(Html::img($assets->baseUrl.'/images/moderator.png', Yii::t('BbiiModule.bbii', 'Moderate'), array('title' => Yii::t('BbiiModule.bbii', 'Moderate'),'style' => 'vertical-align:bottom;')), array('moderator/approval'));
			//if($this->isAdmin()) echo ' | ' . Html::a(Html::img($assets->baseUrl.'/images/config.png', Yii::t('BbiiModule.bbii', 'Forum settings'), array('title' => Yii::t('BbiiModule.bbii', 'Forum settings'),'style' => 'vertical-align:bottom;')), array('setting/index'));
		?>
		</div>
	<?php }; ?>
<?php /*
	<div class="bbii-title"><?= $this->module->forumTitle; ?></div>
	<table style="margin:0;"><tr><td style="padding:0;">
		<div id="bbii-menu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items' => $item
		)); ?>
		</div>
	</td></tr></table>
</div>
<?php if(isset($this->bbii_breadcrumbs)):?>
	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'homeLink' => false,
		'links' => $this->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif?>
*/ ?>