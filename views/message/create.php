<?php

use yii;

/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $count Array */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	(Yii::$app->requestedAction->id == 'create')?Yii::t('BbiiModule.bbii', 'New message'):Yii::t('BbiiModule.bbii', 'Reply'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Inbox') 	.' ('. $count['inbox'] .')', 	'url' => array('message/inbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'Outbox') 	.' ('. $count['outbox'] .')', 	'url' => array('message/outbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'New message'), 								'url' => array('message/create'))
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item, 'count' => $count)); ?>

	<h1><?php echo (Yii::$app->requestedAction->id == 'create')?Yii::t('BbiiModule.bbii', 'New message'):Yii::t('BbiiModule.bbii', 'Reply'); ?></h1>

    <div class="well clearfix">
	   <?php echo $this->render('_form', array('model' => $model)); ?>
    </div>
</div>
