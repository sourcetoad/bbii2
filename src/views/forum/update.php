<?php
/* @var $this ForumController */
/* @var $forum BbiiForum */
/* @var $topic BbiiTopic */
/* @var $post BbiiPost */

/* $this->context->bbii_breadcrumbs = array(
    Yii::t('bbii', 'Forum') => array('/forum/forum/index'),
    $forum->name => array('/forum/forum/forum', 'id' => $forum->id),
    $topic->title => array('/forum/forum/topic', 'id' => $topic->id),
    Yii::t('bbii', 'Change'),
); */

$this->title = Yii::t('forum', $topic->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum_name', $forum->name), 'url' => array('forum/forum', 'id' => $forum->id)];
$this->params['breadcrumbs'][] = ['label' => Yii::t('topic_title', $topic->title), 'url' => array('forum/topic', 'id' => $topic->id)];
$this->params['breadcrumbs'][] = "Change";

$item = array(
    array('label' => Yii::t('bbii', 'Forum'), 'url' => array('/forum/forum/index')),
    array('label' => Yii::t('bbii', 'Members'), 'url' => array('/forum/member/index'))
);
?>
<div id="bbii-wrapper" class="well clearfix">
    <?php echo $this->render('_header', array('item' => $item)); ?>
    
    <?php echo $this->render('_form', array('post' => $post)); ?>
</div>
