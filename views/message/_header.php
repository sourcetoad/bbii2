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
	<?php if (!\Yii::$app->user->isGuest): ?>
		<div class = "pull-right">
		<?php echo Html::a(Yii::t('BbiiModule.bbii', 'Forum'), array('forum/index'),array('class'=>'btn btn-warning')); ?>
		</div>
	<?php endif; ?>
	<h2><?php echo Yii::t('BbiiModule.bbii', 'Private messages'); ?></h2>
    <br />
    <div id = "nav" class="clearfix">
    <?php  echo Nav::widget([
        'items' => $item,
        'options' => array('class'=>'nav nav-pills')
    ]); ?>
    </div>
</div>
<?php if (isset($this->context->bbii_breadcrumbs)):?>
	<?php /*echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	));*/ ?><!-- breadcrumbs -->
<?php endif?>

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

<?php if (isset($count) && is_array($count)) {
	$percentFull = ($count[$box] < 100) ? $count[$box] : 100;
	echo '<div class = "progress"><div class = "progress-bar" role="progress" aria-valuenow="'.$percentFull.'" aria-valuemin="0" aria-valuemax="100" style = "width:'.$percentFull.'%"> </div></div>';
}; ?>
