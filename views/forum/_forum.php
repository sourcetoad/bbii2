<?php

use frontend\modules\bbii\components\DateTimeCalculation;

use yii\helpers\Html;
use yii\widgets\ListView;

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

<?php if($data->type) { ?>
<div class="forum">
	<div class="forum-cell <?= $image; ?>"></div>
	<div class="forum-cell main">
		<div class="header2">
			<?= Html::a(Html::encode($data->name), array('forum', 'id' => $data->id)); ?>
		</div>
		<div class="header4">
			<?= Html::encode($data->subtitle); ?>
		</div>
	</div>
	<div class="forum-cell center">
		<?= Html::encode($data->num_posts); ?><br>
		<?= Html::encode($data->getAttributeLabel('num_posts')); ?>
	</div>
	<div class="forum-cell center">
		<?= Html::encode($data->num_topics); ?><br>
		<?= Html::encode($data->getAttributeLabel('num_topics')); ?>
	</div>
	<div class="forum-cell last-cell">
		<?php if($data->last_post_id && $data->lastPost) {
			echo Html::encode($data->lastPost->poster->member_name);
			echo Html::a(Html::img($this->module->getRegisteredImage('next.png'), 'next', array('style' => 'margin-left:5px;')), array('topic', 'id' => $data->lastPost->topic_id, 'nav' => 'last'));
			echo '<br>';
			echo DateTimeCalculation::long($data->lastPost->create_time);
		} else {
			echo Yii::t('BbiiModule.bbii', 'No posts');
		}
		?>
	</div>
</div>

<?php } else { ?>
	<?php if($index > 0) { echo '</div>'; } ?>
	<div class="forum-category" onclick="BBii.toggleForumGroup(<?= $data->id; ?>,'<?= Yii::$app->createAbsoluteUrl($this->module->id.'/forum/setCollapsed'); ?>');">
		<div class="header2">
			<?= Html::encode($data->name); ?>
		</div>
		<div class="header4">
			<?= Html::encode($data->subtitle); ?>
		</div>
	</div>
	<div class="forum-group" id="category_<?= $data->id; ?>" <?php if($this->collapsed($data->id)) { echo 'style="display:none;"';}?>>
<?php }; ?>

<?php if($index == $lastIndex) { echo '</div>'; } ?>
