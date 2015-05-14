<?php

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $data BbiiTopic */
?>

<div class = "topic">
	<div class = "forum-cell <?php echo $this->topicIcon($data); ?>"></div>
	<div class = "forum-cell main">
		<div class = "header2">
			<?php echo Html::a(Html::encode($data->title), array('topic', 'id' => $data->id), array('class' => $data->hasPostedClass())); ?>
		</div>
		<div class = "header4">
			<?php echo Yii::t('BbiiModule.bbii', 'Started by') . ': ' . Html::encode($data->starter->member_name);?>
			<?php echo ' ' . Yii::t('BbiiModule.bbii', 'on') . ' ' . DateTimeCalculation::medium($data->firstPost->create_time); ?>
		<?php if($this->context->isModerator()): ?>
			<?php echo Html::img($asset->baseUrl.'empty.png'), 'empty'); ?>
			<?php echo Html::img($asset->baseUrl.'update.png'), 'update', array('title' => Yii::t('BbiiModule.bbii', 'Update topic'), 'style' => 'cursor:pointer', 'onclick' => 'BBii.updateTopic(' . $data->id . ', "' . $this->createAbsoluteUrl('moderator/topic') . '")')); ?>
		<?php endif; ?>
		</div>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($data->num_replies); ?><br>
		<?php echo Html::encode($data->getAttributeLabel('num_replies')); ?>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($data->num_views); ?><br>
		<?php echo Html::encode($data->getAttributeLabel('num_views')); ?>
	</div>
	<div class = "forum-cell last-cell">
		<?php 
			echo Html::encode($data->lastPost->poster->member_name);
			echo Html::a(Html::img($asset->baseUrl.'next.png'), 'next', array('style' => 'margin-left:5px;')), array('topic', 'id' => $data->id, 'nav' => 'last'));
			echo '<br>';
			echo DateTimeCalculation::long($data->lastPost->create_time);
		?>
	</div>
</div>