<?php

use frontend\modules\bbii\controllers\ForumController;

use yii\helpers\Html;
use yii\i18n\Formatter;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $data BbiiTopic */
?>

<div class = "topic well">
	<div class = "forum-cell <?php echo ForumController::topicIcon($model); ?>"></div>
	<div class = "forum-cell main">
		<div class = "header2">
			<?php echo Html::a(
				Html::encode($model->title),
				array('topic', 'id' => $model->id), array('class' => $model->hasPostedClass())
			); ?>
		</div>

		<div class = "header4">
			<?php echo Yii::t('BbiiModule.bbii', 'Started by') . ': ' . Html::encode($model->starter->member_name);?>
			<?php echo ' ' . Yii::t('BbiiModule.bbii', 'on') . ' ' . Yii::$app->formatter->asDatetime($model->firstPost->create_time); ?>
			<?php if ($this->context->isModerator()) { ?>
				<?php echo Html::img($assets->baseUrl.'/images/empty.png', 'empty'); ?>
				<?php echo Html::img($assets->baseUrl.'/images/update.png', 'update', array('title' => Yii::t('BbiiModule.bbii', 'Update topic'), 'style' => 'cursor:pointer', 'onclick' => 'BBii.updateTopic(' . $model->id . ', "' . Yii::$app->urlManager->createAbsoluteUrl('moderator/topic') . '")')); ?>
			<?php }; ?>
		</div>

	</div>
	<!--
	<div class = "forum-cell center">
		<?php echo Html::encode($model->num_replies); ?><br>
		<?php echo Html::encode($model->getAttributeLabel('num_replies')); ?>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($model->num_views); ?><br>
		<?php echo Html::encode($model->getAttributeLabel('num_views')); ?>
	</div>
	-->
	<div class = "forum-cell last-cell">
		<?php 
			// echo Html::encode($model->lastPost->poster->member_name);
			echo Html::a(Html::img($assets->baseUrl.'/images/next.png', 'next', array('style' => 'margin-left:5px;')), array('topic', 'id' => $model->id, 'nav' => 'last'));
			echo '<br>';

            $postDateLong = Yii::$app->formatter->asDatetime($model->lastPost->create_time);
            $postDate = Yii::$app->formatter->asDatetime($model->lastPost->create_time, $format='php:M d, Y');

            echo '<span class="hidden-xs">'.$postDateLong.'</span>';
            echo '<span class="visible-xs">'.$postDate.'</span>';

		?>
	</div>
</div>
