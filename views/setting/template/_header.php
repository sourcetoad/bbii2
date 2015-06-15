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
		<div class = "pull-right">
		<?php echo Html::a(Yii::t('BbiiModule.bbii', 'Forum'), array('forum/index'),array('class'=>'btn btn-warning')); ?>
		</div>
	<?php endif; ?>
	<h2><?php echo Yii::t('BbiiModule.bbii', 'Forum settings'); ?></h2>
    <br />
    <div id = "nav" class="clearfix">
        <?php  echo Nav::widget([
            'items' => $item,
            'options' => ['class' =>'nav nav-pills']
        ]); ?>
    </div>
</div>

<?php /*if (isset($this->context->bbii_breadcrumbs)):?>
	<?php echo Breadcrumbs::widget(array(
		'homeLink' => false,
		'links' => $this->context->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif*/?>

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
