<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\i18n\Formatter;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $model BbiiPost */
/* @var $postId integer */
?>
<div class="post well">
    <div class="row">
        <div class="col-xs-3 col-sm-2 text-center">
            <?php //echo Html::tag('a', array('name' => $model->id)); ?>

            <div class = "member-cell">
                <div class = "membername">
                    <?php echo Html::a(Html::encode($model->poster->member_name), array('member/view', 'id' => $model->poster->id)); ?>
                </div>
                <div class = "avatar">
                    <?php echo Html::img((isset($model->poster->avatar) ? \Yii::$app->request->baseUrl . $this->module->avatarStorage . '/'. $model->poster->avatar : $assets->baseUrl.'/images/empty.jpeg'),
                                     array(
                                       'title' => 'avatar',
                                       'class' => 'img-responsive img-circle')
                                    );
                  ?>
                </div>
                <div class = "group">
                    <?php if (isset($model->poster->group)) {
                        if (isset($model->poster->group->image)) {
                            echo Html::img( $assets->baseUrl.'/'.$model->poster->group->image, array('title' => 'group')) . '<br>';
                        }
                        echo Html::encode($model->poster->group->name);
                    } ?>
                </div>
                <div class = "memberinfo">
                    <?php echo Yii::t('BbiiModule.bbii', 'Posts') . ': ' . Html::encode($model->poster->posts); ?><br>
                    <span class="hidden-xs">
                        <?php echo Yii::t('BbiiModule.bbii', 'Joined') . ': ' . \Yii::$app->formatter->asDate($model->poster->first_visit); ?>
                    </span>
                </div>
                <div style = "text-align:center;margin-top:10px;">
                <?php //@todo no rep mod for init release, no extra features either - DJE : 2015-05-29 ?>
                <?php /* if (!\Yii::$app->user->isGuest) { ?>
                    <?php echo Html::img($assets->baseUrl.'/images/warn.png', array('title' => Yii::t('BbiiModule.bbii', 'Report post'), 'style' => 'cursor:pointer;', 'onclick' => 'reportPost(' . $model->id . ')')); ?>
                    <?php echo Html::a( Html::img($assets->baseUrl.'/images/pm.png', array('title' => Yii::t('BbiiModule.bbii', 'Send private message'))), array('message/create', 'id' => $model->user_id) ); ?>
                    <?php echo $this->showUpvote($model->id); ?>
                <?php };*/ ?>
                </div>
            </div>
        </div>
        <div class="col-xs-9 col-sm-10">
            <div class="post-cell">
                <div class = "post-header">

                    <div class="pull-right">
                        <?php if (!(\Yii::$app->user->isGuest || $model->topic->locked) || $this->context->isModerator()) { ?>
                            <?php $form = ActiveForm::begin([
                                'action'               => array('forum/quote', 'id' => $model->id),
                                'enableAjaxValidation' => false,
                            ]); ?>
                                <?php echo Html::submitButton(Yii::t('BbiiModule.bbii',
                                                                     '<span class="glyphicon glyphicon-pencil"></span> Quote'),
                                                              array('class' => 'btn btn-primary btn-xs btn-round pull-right')); ?>
                            <?php ActiveForm::end(); ?>
                        <?php }; ?>

                        <?php if (!($model->user_id != \Yii::$app->user->identity->id  || $model->topic->locked) || $this->context->isModerator()) { ?>
                            <?php $form = ActiveForm::begin([
                                'action'               => array('forum/update', 'id' => $model->id),
                                'enableAjaxValidation' => false,
                            ]); ?>
                                <?php echo Html::submitButton(Yii::t('BbiiModule.bbii',
                                                                     '<span class="glyphicon glyphicon-pencil"></span> Edit'),
                                                              array('class' => 'btn btn-primary btn-xs btn-round pull-right')); ?>
                            <?php ActiveForm::end(); ?>
                        <?php }; ?>
                    </div>

                    <div class = "header2<?php echo (isset($postId) && $postId == $model->id)?' target':''; ?>"><?php echo Html::encode($model->subject); ?></div>
                    <?php echo Html::encode($model->poster->member_name); ?>
                    <?php echo \Yii::$app->formatter->asDatetime($model->create_time); ?>
                    <?php echo '<span class = "badge" title = "' . Yii::t('BbiiModule.bbii','Reputation') . '">' . $model->upvoted . '</span>'; ?>
                </div>

                <?php //@todo Poll disabled for init release - DJE : 2015-05-28
                /* if ($this->poll !== null && $this->poll->post_id == $model->id): ?>
                <div class = "bbii-poll">
                    <strong><?php echo Yii::t('BbiiModule.bbii', 'Poll') . ': ' .$this->poll->question; ?></strong>
                    <div id = "poll">
                    <?php if ($this->voted): ?>
                        <?php $this->widget('zii.widgets.CListView', array(
                            'id' => 'bbiiPoll',
                            'dataProvider' => $this->choiceProvider,
                            'itemView' => '_pollResult',
                            'summaryText' => false,
                        ));
                        echo '<div style = "text-align:center;width:99%">';
                        if ($this->poll->user_id == \Yii::$app->user->identity->id  || $this->context->isModerator()) {
                            echo Html::button(Yii::t('BbiiModule.bbii', 'Edit poll'), array('onclick' => 'editPoll(' . $this->poll->id . ', "' . \Yii::$app->urlManager->createAbsoluteUrl('forum/editPoll') . '");'));
                        }
                        if (!\Yii::$app->user->isGuest && $this->poll->allow_revote && (!isset($this->poll->expire_date) || $this->poll->expire_date > date('Y-m-d'))) {
                            echo Html::button(Yii::t('BbiiModule.bbii', 'Change vote'), array('onclick' => 'changeVote(' . $this->poll->id . ', "' . \Yii::$app->urlManager->createAbsoluteUrl('forum/displayVote') . '");'));
                        }
                        echo '</div>';
                        ?>
                    <?php else: ?>
                        <?php echo Html::form('', 'post', array('id' => 'bbii-poll-form'));
                        echo Html::hiddenField('poll_id', $this->poll->id);
                        $this->widget('zii.widgets.CListView', array(
                            'id' => 'bbiiPoll',
                            'dataProvider' => $this->choiceProvider,
                            'itemView' => '_pollChoice',
                            'summaryText' => false,
                        ));
                        echo '<div style = "text-align:right;width:50%">';
                        echo Html::button(Yii::t('BbiiModule.bbii', 'Vote'), array('onclick' => 'vote("' . \Yii::$app->urlManager->createAbsoluteUrl('forum/vote') . '");'));
                        echo '</div>';
                        echo Html::endForm(); ?>
                    <?php endif; ?>
                    </div>
                </div>
                <?php endif; */ ?>
                <div class = "post-content">
                    <?php echo $model->content; ?>
                </div>
                <div class = "signature">
                    <?php echo $model->poster->signature; ?>
                </div>
                <div class = "post-footer">
                    <?php if ($model->change_reason) { ?>
                        <?php echo Yii::t('BbiiModule.bbii','Changed'). ': ' . \Yii::$app->formatter->asDatetime($model->change_time) . ' ' . Yii::t('BbiiModule.bbii','Reason') . ': ' . Html::encode($model->change_reason); ?>
                    <?php }; ?>
                </div>
                <?php // @todo rep mod disabled for init release - DJE : 2015-05-28 ?>
                <?php // echo $this->render('_upvotedBy', array('post_id' => $model->id)); ?>
                <div class = "toolbar">
                <?php if ($this->context->isModerator()): ?>
                    <?php echo Html::a( Html::img($assets->baseUrl.'/images/warn.png', 	array('title' => Yii::t('BbiiModule.bbii', 'Warn user'))), array('message/create', 'id' => $model->user_id, 'type' => 1) ); ?>
                    <?php echo Html::img($assets->baseUrl.'/images/delete.png',			array('title' => Yii::t('BbiiModule.bbii', 'Delete post'), 'style' => 'cursor:pointer;', 'onclick' => 'if (confirm("' . Yii::t('BbiiModule.bbii','Do you really want to delete this post?') . '")) { deletePost("' . \Yii::$app->urlManager->createAbsoluteUrl('moderator/delete', array('id' => $model->id)) . '") }')); ?>
                    <?php echo Html::img($assets->baseUrl.'/images/ban.png',			array('title' => Yii::t('BbiiModule.bbii', 'Ban IP address'), 'style' => 'cursor:pointer;', 'onclick' => 'if (confirm("' . Yii::t('BbiiModule.bbii','Do you really want to ban this IP address?') . '")) { banIp(' . $model->id . ', "' . \Yii::$app->urlManager->createAbsoluteUrl('moderator/banIp') . '") }')); ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
