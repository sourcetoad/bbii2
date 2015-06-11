<?php

use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiSession;
use frontend\modules\bbii\models\BbiiSpider;
use frontend\modules\bbii\models\BbiiTopic;

use yii\helpers\Html;

/* @var $this ForumController */
$members = BbiiMember::find()->present()->count();
$newest  = BbiiMember::find()->newest()->one();
$present = BbiiSession::find()->present()->count();

?>
<div class="col col-md-12">
    <div class="well">
        <div class="clearfix">
            <div class="col-sm-12 col-md-8 online">
                <h5>
                    <?php echo Yii::t('BbiiModule.bbii','{0} guest(s) and {1} active member(s)', array(($present - $members) > 0 ?: 0,$members));?>
                    <small><?php echo Yii::t('BbiiModule.bbii','(in the past 15 minutes)');?></small>
                </h5>

                <?php $members = BbiiMember::find()->present()->show()->findAll();
                    foreach($members as $member) {
                        echo Html::a($member->member_name, array('member/view', 'id' => $member->id), array('style' => 'color:#'.$member->group->color)) . '&nbsp;';
                    }
                    $spiders = BbiiSpider::find()->present()->findAll();
                    foreach($spiders as $spider) {
                        echo Html::a($spider->name, $spider->url, array('class' => 'spider','target' => '_new')) . '&nbsp;';
                    }
                ?>
                <?php echo Yii::t('BbiiModule.bbii','({0} anonymous member(s))', array(BbiiMember::find()->hidden()->present()->count())); ?>

            </div>
            <div class="col-sm-12 col-md-4 statistics">
                <h5>
                    <?php echo Yii::t('BbiiModule.bbii','Board Statistics'); ?>
                </h5>
                <ul>
                    <li><?php echo Yii::t('BbiiModule.bbii','Total topics'); ?> <?php echo BbiiTopic::find()->count(); ?></li>
                    <li><?php echo Yii::t('BbiiModule.bbii','Total posts'); ?> <?php echo BbiiPost::find()->count(); ?></li>
                    <li><?php echo Yii::t('BbiiModule.bbii','Total members'); ?> <?php echo BbiiMember::find()->count(); ?></li>
                    <li><?php echo Yii::t('BbiiModule.bbii','Newest member'); ?>
                    <?php
                        if ($newest->member_name != NULL) {
                            echo Html::a($newest->member_name, array('member/view', 'id' => $newest->id));
                        }
                    ?></li>
                    <li><?php echo Yii::t('BbiiModule.bbii','Visitors today'); ?> <?php echo BbiiSession::find()->count(); ?></li>
                </ul>

            </div>
        </div>
    </div>
</div>
