<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this MessageController */
/* @var $item array */
?>
<div id = "bbii-header">
	<?php if (!Yii::$app->user->isGuest): ?>
		<div class = "bbii-profile-box">
		<?php echo Html::a(Yii::t('BbiiModule.bbii', 'Forum'), array('forum/index')); ?>
		</div>
	<?php endif; ?>
	<div class = "bbii-title"><?php echo Yii::t('BbiiModule.bbii', 'Private messages'); ?></div>
	<table style = "margin:0;"><tr><td style = "padding:0;">
		<div id = "bbii-menu">
		<?php  echo Nav::widget([
		    'items' => $item,
		]); ?>
		</div>
	</td></tr></table>
</div>
<?php if (isset($this->context->bbii_breadcrumbs)):?>
	<?php /*echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	));*/ ?><!-- breadcrumbs -->
<?php endif?>

<?php if (isset($count) && is_array($count)) {
	$percentFull = ($count[$box] < 100) ? $count[$box] : 100;
	echo '<div class = "progress"><div class = "progressbar" style = "width:'.$percentFull.'%"> </div></div>';
}; ?>