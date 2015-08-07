<?php

use sourcetoad\bbii2\controllers\ForumController;

use yii\helpers\Html;
use yii\i18n\Formatter;

use sourcetoad\bbii2\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $data BbiiTopic */
?>

<tr>
    <td class="forum-icon">
        <i class="forum-cell <?php echo ForumController::topicIcon($model); ?>"></i>
    </td>
    <td class="forum-cell main">
        <div class = "header2">
            <?php echo Html::a(
                Html::encode($model->title),
                array('topic', 'id' => $model->id), array('class' => $model->hasPostedClass())
            ); ?>
        </div>
        <div class = "header4">
            <?php
                $startDateLong = \Yii::$app->formatter->asDatetime($model->firstPost->create_time);
                $startDate = \Yii::$app->formatter->asDatetime($model->firstPost->create_time, $format='php: M d, Y');
            ?>
            <?php echo Yii::t('BbiiModule.bbii', 'Started by') . ': ' . Html::encode($model->starter->member_name);?>
            <?php echo ' ' . Yii::t('BbiiModule.bbii', 'on') . ' <span class="visible-xs">' . $startDate . '</span><span class="hidden-xs">' . $startDateLong . '</span>'; ?>

            <?php if ($this->context->isModerator()) { ?>
                <?php echo Html::img($assets->baseUrl.'/images/empty.png', ['alt' => 'empty']); ?>
                <?php
                echo Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                    [
                        'setting/update',
                        'id'   => $model->id,
                        'type' => 'topic',
                    ],
                    ['topic', 'nav' => 'last']
                ); ?>
            <?php }; ?>
        </div>
    </td>
    <td class="forum-cell last-cell">
        <?php
            echo Html::encode($model->lastPost->poster->member_name);
            echo Html::a(
                Html::img($assets->baseUrl.'/images/next.png', ['alt' => 'next', 'style' => 'margin-left:5px;']),
                ['topic', 'id' => $model->id, 'nav' => 'last']
            );
            echo '<br>';

            $postDateLong = \Yii::$app->formatter->asDatetime($model->lastPost->create_time);
            $postDate     = \Yii::$app->formatter->asDatetime($model->lastPost->create_time, $format='php:M d, Y');
            echo '<span class="topic-date hidden-xs">'.$postDateLong.'</span>';
            echo '<span class="topic-date visible-xs">'.$postDate.'</span>';

        ?>
    </td>
</tr>
