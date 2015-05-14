<?php
/* @var $this MessageController */
/* @var $item array */
?>
	<div id = "bbii-header">
		<?php if(!Yii::$app->user->isGuest): ?>
			<div class = "bbii-profile-box">
			<?php echo CHtml::link(Yii::t('BbiiModule.bbii', 'Forum'), array('forum/index')); ?>
			</div>
		<?php endif; ?>
		<div class = "bbii-title"><?php echo Yii::t('BbiiModule.bbii', 'Private messages'); ?></div>
		<table style = "margin:0;"><tr><td style = "padding:0;">
			<div id = "bbii-menu">
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