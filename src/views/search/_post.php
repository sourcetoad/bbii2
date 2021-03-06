<?php
/* @var $this SearchController */
/* @var $data BbiiPost */
?>

<div class = "post">
    <div class = "fade">
        <div class = "header2 pad5">
            <?php echo Html::a(Html::encode($data->subject), array('forum/topic', 'id' => $data->topic_id, 'nav' => $data->id)); ?>
        </div>
        <div class = "header4">
            <?php echo '; ' . Html::encode($data->poster->member_name); ?>
            <?php echo '; ' . DateTimeCalculation::full($data->create_time); ?>
            <?php echo Yii::t('BbiiModule.bbii','in'); ?>
            <?php echo Html::a($data->forum->name, array('forum/forum', 'id' => $data->forum_id)); ?>
        </div>
    </div>
    <hr>
    <div class = "margin5">
        <?php echo $this->getSearchedString($data->content, 10); ?>
    </div>
    <hr>
    <div class = "margin5">
        <?php echo Html::a(Yii::t('BbiiModule.bbii','View Topic'), array('forum/topic', 'id' => $data->topic_id)); ?>
    </div>
</div>