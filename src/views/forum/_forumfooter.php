<?php

use sourcetoad\bbii2\models\BbiiForum;
use sourcetoad\bbii2\models\BbiiMember;
use sourcetoad\bbii2\models\BbiiMessage;
use sourcetoad\bbii2\models\BbiiPost;
use sourcetoad\bbii2\models\BbiiSession;
use sourcetoad\bbii2\models\BbiiTopic;

use yii\helpers\Html;

$member = BbiiMember::find()->newest()->one();

/* @var $this ForumController */
?>
<div class="well clearfix">
    <div class="row">
        <div class="col col-sm-12 col-md-8 legend">
            <h4>
                <?php echo Yii::t('BbiiModule.bbii','Forum legend'); ?>
            </h4>
            <ul class="list-group">
                <li class="list-group-item">
                    <i class = "forum-cell topic1"></i>
                    <?php echo Yii::t('BbiiModule.bbii','Unread topic'); ?>
                </li>
                <li class="list-group-item">
                    <i class = "forum-cell topic1s"></i>
                    <?php echo Yii::t('BbiiModule.bbii','Sticky topic'); ?>
                </li>
                <li class="list-group-item">
                    <i class = "forum-cell topic1g"></i>
                    <?php echo Yii::t('BbiiModule.bbii','Global topic'); ?>
                </li>
                <li class="list-group-item">
                    <i class = "forum-cell topic2"></i>
                    <?php echo Yii::t('BbiiModule.bbii','Read topic'); ?>
                </li>
                <li class="list-group-item">
                    <i class = "forum-cell topic1l"></i>
                    <?php echo Yii::t('BbiiModule.bbii','Locked topic'); ?>
                </li>
                <?php // @todo Polls disabled for init release - DJE : 2015-05-29 ?>
                <?php /*
                <li class="list-group-item">
                    <span class = "forum-cell topic1p"></span>
                    <?php echo Yii::t('BbiiModule.bbii','Poll'); ?>
                </li>
                */?>
            </ul>

        </div>
        <div class="col col-sm-12 col-md-4 statistics">
            <h4>
                <?php echo Yii::t('BbiiModule.bbii','Board Statistics'); ?>
            </h4>
            <ul class="list-group">
                <li class="list-group-item">
                    <?php echo Yii::t('BbiiModule.bbii','Total topics'); ?>
                    <div class="badge pull-right"><?php echo BbiiTopic::find()->count(); ?></div>
                </li>
                <li class="list-group-item">
                    <?php echo Yii::t('BbiiModule.bbii','Total posts'); ?>
                    <div class="badge pull-right"><?php echo BbiiPost::find()->count(); ?></div>
                </li>
                <li class="list-group-item">
                    <?php echo Yii::t('BbiiModule.bbii','Total members'); ?>
                    <div class="badge pull-right"><?php echo BbiiMember::find()->count(); ?></div>
                </li>
                <li class="list-group-item">
                    <?php echo Yii::t('BbiiModule.bbii','Newest member'); ?>
                    <div class="pull-right"><?php echo Html::a($member->member_name, array('member/view', 'id' => $member->id)); ?></div>
                </li>
                <li class="list-group-item">
                    <?php echo Yii::t('BbiiModule.bbii','Visitors today'); ?>
                    <div class="badge pull-right"><?php echo BbiiSession::find()->count(); ?></div>
                </li>
            </ul>
        </div>
    </div>
</div>
