<?php

use frontend\modules\bbii\controllers\ForumController;

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $model BbiiForum */

$image = 'forum';
if (!isset($model->last_post_id) || ForumController::forumIsRead($model->id)) {
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
<div class = "forum well">
    <table class="table table-striped table-condensed">
        <tr>
            <td class = "forum-cell">
                <i class="forum-cell <?php echo $image; ?>"></i>
            </td>
            <td>
                <span class = "header2">
                    <?php echo Html::a(Html::encode($model->name), array('forum', 'id' => $model->id)); ?>
                </span><br>
                <span class = "header4">
                    <?php echo Html::encode($model->subtitle); ?>
                </span>
            </td>
            <td class = "forum-cell forum-posts center">
                <?php echo Html::encode($model->num_posts); ?><br>
                <?php echo Html::encode($model->getAttributeLabel('num_posts')); ?>
            </td>
            <td class = "forum-cell forum-posts center">
                <?php echo Html::encode($model->num_topics); ?><br>
                <?php echo Html::encode($model->getAttributeLabel('num_topics')); ?>
            </td>
            <td class = "forum-cell forum-date last-cell">
                <?php
                if (is_numeric($model->last_post_id) && !empty($model->lastPost)) {
                    echo Html::a(Html::img($assets->baseUrl.'/images/next.png', array('alt' => 'next', 'style' => 'margin-left:5px;')), array('topic', 'id' => $model->lastPost->topic_id, 'nav' => 'last'));
                    echo Html::encode($model->lastPost->poster->member_name);
                    echo '<br>';

                    // Responive date formate display
                    $forumDateLong = \Yii::$app->formatter->asDatetime($model->lastPost->create_time);
                    $forumDate = \Yii::$app->formatter->asDatetime($model->lastPost->create_time, $format='php:M d, Y');
                    echo '<span class="hidden-xs">'.$forumDateLong.'</span>';
                    echo '<span class="visible-xs">'.$forumDate.'</span>';

                } else {
                    echo Yii::t('BbiiModule.bbii', 'No posts');
                }
                ?>
            </td>
        </tr>
    </table>
</div>

<?php } else { ?>
	<? // @todo re-enable collapsable feature - DJE : 2015-05-26 ?>
	<?php /*
	<?php if ($index > 0) { echo '</div>'; } ?>
	<div class = "forum-category" onclick = "BBii.toggleForumGroup(<?php echo $model->id; ?>,'<?php echo \Yii::$app->urlManager->createAbsoluteUrl($this->context->module->id.'/forum/setCollapsed'); ?>');">
		<div class = "header2">
			<?php echo Html::encode($model->name); ?>
		</div>
		<div class = "header4">
			<?php echo Html::encode($model->subtitle); ?>
		</div>
	</div>
	<div class = "forum-group" id = "category_<?php echo $model->id; ?>" <?php if ($this->collapsed($model->id)) { echo 'style = "display:none;"';}?>>
	*/ ?>
<?php }; ?>

<?php if ($index == $lastIndex) { echo '</div>'; } ?>
