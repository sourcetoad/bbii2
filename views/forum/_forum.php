<?php

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $data BbiiForum */

	$image = 'forum';
	if(!isset($data->last_post_id) || $this->forumIsRead($data->id)) {
		$image .= '2';
	} else {
		$image .= '1';
	}
	if($data->locked) {
		$image .= 'l';
	}
	if($data->moderated) {
		$image .= 'm';
	}
	if(!$data->public) {
		$image .= 'h';
	}
?>

<?php if($data->type): ?>
<div class = "forum">
	<div class = "forum-cell <?php echo $image; ?>"></div>
	<div class = "forum-cell main">
		<div class = "header2">
			<?php echo Html::a(Html::encode($data->name), array('forum', 'id' => $data->id)); ?>
		</div>
		<div class = "header4">
			<?php echo Html::encode($data->subtitle); ?>
		</div>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($data->num_posts); ?><br>
		<?php echo Html::encode($data->getAttributeLabel('num_posts')); ?>
	</div>
	<div class = "forum-cell center">
		<?php echo Html::encode($data->num_topics); ?><br>
		<?php echo Html::encode($data->getAttributeLabel('num_topics')); ?>
	</div>
	<div class = "forum-cell last-cell">
		<?php if($data->last_post_id && $data->lastPost) {
			echo Html::encode($data->lastPost->poster->member_name);
			echo Html::a(Html::img($asset->baseUrl.'next.png'), 'next', array('style' => 'margin-left:5px;')), array('topic', 'id' => $data->lastPost->topic_id, 'nav' => 'last'));
			echo '<br>';
			echo DateTimeCalculation::long($data->lastPost->create_time);
		} else {
			echo Yii::t('BbiiModule.bbii', 'No posts');
		}
		?>
	</div>
</div>

<?php else: ?>
	<?php if($index > 0) { echo '</div>'; } ?>
	<div class = "forum-category" onclick = "BBii.toggleForumGroup(<?php echo $data->id; ?>,'<?php echo Yii::$app->createAbsoluteUrl($this->module->id.'/forum/setCollapsed'); ?>');">
		<div class = "header2">
			<?php echo Html::encode($data->name); ?>
		</div>
		<div class = "header4">
			<?php echo Html::encode($data->subtitle); ?>
		</div>
	</div>
	<div class = "forum-group" id = "category_<?php echo $data->id; ?>" <?php if($this->collapsed($data->id)) { echo 'style = "display:none;"';}?>>
<?php endif; ?>

<?php if($index == $lastIndex) { echo '</div>'; } ?>
