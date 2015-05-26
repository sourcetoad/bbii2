<?php

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $model BbiiForum */

$image = 'forum';
if (!isset($model->last_post_id) || $this->forumIsRead($model->id)) {
	$image .= '2';
} else {
	$image .= '1';
}
if ($model->locked) {
	$image .= 'l';
}
if ($model->moderated) {
	$image .= 'm';
}
if (!$model->public) {
	$image .= 'h';
}
?>

<?php if ($model->type) { ?>
<div class = "forum">
	<div class = "forum-cell <?php echo $image; ?>"></div>
	<div class = "forum-cell main">
		<div class = "header2">
			<?php echo Html::a(Html::encode($model->name), array('forum', 'id' => $model->id)); ?>
		</div>
		<div class = "header4">
			<?php echo Html::encode($model->subtitle); ?>
		</div>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($model->num_posts); ?><br>
		<?php echo Html::encode($model->getAttributeLabel('num_posts')); ?>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($model->num_topics); ?><br>
		<?php echo Html::encode($model->getAttributeLabel('num_topics')); ?>
	</div>
	<div class = "forum-cell last-cell">
		<?php if ($model->last_post_id && $model->lastPost) {
			echo Html::encode($model->lastPost->poster->member_name);
			echo Html::a(Html::img($assets->baseUrl.'/images/next.png', 'next', array('style' => 'margin-left:5px;')), array('topic', 'id' => $model->lastPost->topic_id, 'nav' => 'last'));
			echo '<br>';
			echo DateTimeCalculation::long($model->lastPost->create_time);
		} else {
			echo Yii::t('BbiiModule.bbii', 'No posts');
		}
		?>
	</div>
</div>

<?php } else { ?>
	<?php if ($index > 0) { echo '</div>'; } ?>
	<div class = "forum-category" onclick = "BBii.toggleForumGroup(<?php echo $model->id; ?>,'<?php echo Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/setCollapsed'); ?>');">
		<div class = "header2">
			<?php echo Html::encode($model->name); ?>
		</div>
		<div class = "header4">
			<?php echo Html::encode($model->subtitle); ?>
		</div>
	</div>
	<div class = "forum-group" id = "category_<?php echo $model->id; ?>" <?php //if ($this->collapsed($model->id)) { echo 'style = "display:none;"';}?>>
<?php }; ?>

<?php if ($index == $lastIndex) { echo '</div>'; } ?>
