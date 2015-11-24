<?php

use yii\helpers\Html;

use yii\widgets\ListView;
use yii\web\UrlManager;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this MemberController */
/* @var $userData BbiiMember */
/* @var $dataProvider ActiveDataProvider BbiiPost */
/* @var $topicProvider ActiveDataProvider BbiiTopic*/

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
    Yii::t('BbiiModule.bbii', 'Forum')   => array('forum/index'),
    Yii::t('BbiiModule.bbii', 'Members') => array('member/index'),
    $userData->getAttribute('member_name') . Yii::t('BbiiModule.bbii', '\'s profile'),
);

$item = array(
    array(
      'label'   => Yii::t('BbiiModule.bbii', 'Forum'),
      'url'     => array('forum/index')
    ),
    array(
      'label' => Yii::t('BbiiModule.bbii', 'Members'),
      'url'   => array('member/index'),
       'visible' => $this->context->isModerator()
    ),
    array(
      'label' => Yii::t('BbiiModule.bbii', 'Approval'),
      'url'   => array('moderator/approval'), 'visible' => $this->context->isModerator()
    ),
    array(
      'label' => Yii::t('BbiiModule.bbii', 'Posts'),
      'url' => array('moderator/admin'), 'visible' => $this->context->isModerator()
    ),
);
?>

<?php
foreach (\Yii::$app->session->getAllFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
\Yii::$app->session->removeAllFlashes();
?>
<div id="bbii-wrapper" class="well clearfix">
    <?php echo $this->render('_header', array('item' => $item)); ?>

    <div class="well clearfix">
        <div class = "bbii-box-top">
            <?php
                if ($this->context->isModerator() || $userData->getAttribute('id') == \Yii::$app->user->identity->id ) {
                    //echo Html::htmlButton(Yii::t('BbiiModule.bbii', 'Edit profile'), array('class' => 'bbii-button-right', 'onclick' => 'js:document.location.href = "' . \Yii::$app->urlManager->createAbsoluteUrl('member/update', array('id' => $userData->getAttribute('id)) .'"'));
                    /* echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Edit profile'),
                        array(
                            'class'     => 'btn btn-success pull-right',
                            'onclick'   => 'js:document.location.href = "' . \Yii::$app->urlManager->createAbsoluteUrl('forum/member/update', array('id' => $userData->id)) .'"'
                        )
                    ); */
                    echo Html::a(
                        'Edit Profile',
                        array('./member/update/?id='.$userData->id),
                        array('class' => 'btn btn-success pull-right')
                    );
                };
            ?>
            <?php echo Html::encode($userData->getAttribute('member_name')) . Yii::t('BbiiModule.bbii', '\'s profile'); ?>
        </div>
        <hr />
        <div class="col-md-6 col-sm-12 left">
            <table>
              <tr>
                <td rowspan = "4">
                    <?php
                    echo Html::img(
                        (
                            !$userData->getAttribute('avatar')
                            ? $assets->baseUrl.'/images/empty.jpeg'
                            : $assets->baseUrl.'/avatars/'. $userData->getAttribute('avatar')
                        ),
                        [
                            'alt'   => 'avatar',
                            'class' => 'img-responsive img-circle',
                            'title' => 'avatar',

                        ]
                    ); ?>
                </td>
                <td style = "width:200px"><strong><?php echo Yii::t('BbiiModule.bbii', 'Member since'); ?></strong></td>
                <td><?php echo \Yii::$app->formatter->asDatetime($userData->getAttribute('first_visit'), 'long', 'medium'); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo Yii::t('BbiiModule.bbii', 'Last visit'); ?></strong></td>
                <td><?php echo \Yii::$app->formatter->asDatetime($userData->getAttribute('last_visit'), 'long', 'medium'); ?></td>
            </tr>
            <tr>
                <td><strong><?php //echo Yii::t('BbiiModule.bbii', 'Number of posts'); ?></strong></td>
                <td><?php //echo Html::encode($userData->getAttribute('posts')); ?></td>
            </tr>
            <tr>
                <td><strong><?php //echo Yii::t('BbiiModule.bbii', 'Reputation'); ?></strong></td>
                <td><?php //echo Html::encode($userData->getAttribute('upvoted')); ?></td>
            </tr>
            <?php if (!empty($group)){ ?>
            <tr>
                <th style = "text-align:center;"><?php echo Html::encode($userData->getAttribute('member_name')); ?></th>
                <td><strong><?php echo Yii::t('BbiiModule.bbii', 'Group'); ?></strong></td>
                <td><?php $group = $userData->getAttribute('group'); echo Html::encode($userData->getAttribute('group')['name']); ?></td>
            </tr>
            <?php }; ?>
            <tr>
                <td></td>
                <th><?php echo Yii::t('BbiiModule.bbii', 'Location'); ?></th>
                <td><?php $location = $userData->getAttribute('location'); echo (!empty($location))?Html::encode($userData->getAttribute('location')):Yii::t('BbiiModule.bbii', 'Unknown'); ?></td>
            </tr>
            <tr>
                <td></td>
                <th><?php //echo Yii::t('BbiiModule.bbii', 'Birthdate'); ?></th>
                <td><?php //$birthdate = $userData->getAttribute('birthdate'); echo (!empty($birthdate))?\Yii::$app->formatter->asDatetime($userData->getAttribute('birthdate'), 'long', null):Yii::t('BbiiModule.bbii', 'Unknown'); ?></td>
            </tr>
            <?php if ($userData->getAttribute('gender') !== null) { ?>
                <tr>
                    <td></td>
                    <th><?php echo Yii::t('BbiiModule.bbii', 'Gender'); ?></th>
                    <td><?php echo $userData->getAttribute('gender') ? Yii::t('BbiiModule.bbii', 'Female') : Yii::t('BbiiModule.bbii', 'Male'); ?></td>
                </tr>
            <?php }; ?>
            <tr>
                <td></td>
                <th><?php echo Yii::t('BbiiModule.bbii', 'Presence on the internet'); ?></th>
                <td>
                    <?php  //if ($userData->getAttribute('contact_email') && $this->module->userMailColumn)     echo Html::a(Html::img($assets->baseUrl.'/images/User.png', array('alt' => 'e-mail',   'title' => Yii::t('BbiiModule.bbii', 'Contact user by e-mail'))), array('member/mail', 'id' => $userData->getAttribute('id'))); ?>
                    <?php  $blogger     = $userData->getAttribute('blogger');     if (!empty($blogger))     echo Html::a(Html::img($assets->baseUrl.'/images/Blogger.png',     array('alt' => 'Blogger',  'title' => 'Blogger')),     $userData->getAttribute('blogger'),     array('target' => '_blank')); ?>
                    <?php  $facebook     = $userData->getAttribute('facebook');     if (!empty($facebook))     echo Html::a(Html::img($assets->baseUrl.'/images/Facebook.png', array('alt' => 'Facebook', 'title' => 'Facebook')), $userData->getAttribute('facebook'),     array('target' => '_blank')); ?>
                    <?php  $flickr      = $userData->getAttribute('flickr');     if (!empty($flickr))     echo Html::a(Html::img($assets->baseUrl.'/images/Flickr.png',     array('alt' => 'Flickr',   'title' => 'Flickr')),     $userData->getAttribute('flickr'),         array('target' => '_blank')); ?>
                    <?php  $google      = $userData->getAttribute('google');     if (!empty($google))     echo Html::a(Html::img($assets->baseUrl.'/images/Google.png',     array('alt' => 'Google',   'title' => 'Google')),     $userData->getAttribute('google'),         array('target' => '_blank')); ?>
                    <?php  $linkedin     = $userData->getAttribute('linkedin');     if (!empty($linkedin))     echo Html::a(Html::img($assets->baseUrl.'/images/Linkedin.png', array('alt' => 'Linkedin', 'title' => 'Linkedin')), $userData->getAttribute('linkedin'),     array('target' => '_blank')); ?>
                    <?php  $metacafe     = $userData->getAttribute('metacafe');     if (!empty($metacafe))     echo Html::a(Html::img($assets->baseUrl.'/images/Metacafe.png', array('alt' => 'Metacafe', 'title' => 'Metacafe')), $userData->getAttribute('metacafe'),     array('target' => '_blank')); ?>
                    <?php  $myspace     = $userData->getAttribute('myspace');     if (!empty($myspace))     echo Html::a(Html::img($assets->baseUrl.'/images/Myspace.png',     array('alt' => 'Myspace',  'title' => 'Myspace')),     $userData->getAttribute('myspace'),     array('target' => '_blank')); ?>
                    <?php  $orkut          = $userData->getAttribute('orkut');     if (!empty($orkut))        echo Html::a(Html::img($assets->baseUrl.'/images/Orkut.png',     array('alt' => 'Orkut',    'title' => 'Orkut')),     $userData->getAttribute('orkut'),         array('target' => '_blank')); ?>
                    <?php  $tumblr         = $userData->getAttribute('tumblr');     if (!empty($tumblr))     echo Html::a(Html::img($assets->baseUrl.'/images/Tumblr.png',     array('alt' => 'Tumblr',   'title' => 'Tumblr')),     $userData->getAttribute('tumblr'),         array('target' => '_blank')); ?>
                    <?php  $twitter     = $userData->getAttribute('twitter');     if (!empty($twitter))     echo Html::a(Html::img($assets->baseUrl.'/images/Twitter.png',     array('alt' => 'Twitter',  'title' => 'Twitter')),     $userData->getAttribute('twitter'),     array('target' => '_blank')); ?>
                    <?php  $website     = $userData->getAttribute('website');     if (!empty($website))     echo Html::a(Html::img($assets->baseUrl.'/images/Globe.png',     array('alt' => 'Website',  'title' => 'Website')),     $userData->getAttribute('website'),     array('target' => '_blank')); ?>
                    <?php  $wordpress     = $userData->getAttribute('wordpress'); if (!empty($wordpress))    echo Html::a(Html::img($assets->baseUrl.'/images/Wordpress.png',array('alt' => 'Wordpress','title' => 'Wordpress')),$userData->getAttribute('wordpress'),     array('target' => '_blank')); ?>
                    <?php  $yahoo          = $userData->getAttribute('yahoo');     if (!empty($yahoo))        echo Html::a(Html::img($assets->baseUrl.'/images/Yahoo.png',     array('alt' => 'Yahoo',    'title' => 'Yahoo')),     $userData->getAttribute('yahoo'),         array('target' => '_blank')); ?>
                    <?php  $youtube     = $userData->getAttribute('youtube');     if (!empty($youtube))     echo Html::a(Html::img($assets->baseUrl.'/images/Youtube.png',     array('alt' => 'Youtube',  'title' => 'Youtube')),     $userData->getAttribute('youtube'),     array('target' => '_blank')); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <th><?php echo Yii::t('BbiiModule.bbii', 'Personal text'); ?></th>
                <td><?php echo Html::encode($userData->getAttribute('personal_text')); ?></td>
            </tr>
            </table>
        </div>
        <div class="col-md-5 col-sm-12 right">
            <hr class="visible-sm visible-xs" />
            <div class = "header2"><?php echo Yii::t('BbiiModule.bbii','Recent Posts'); ?></div>
                <?php // @depricated 2.2 Kept for referance
                /*$this->widget('zii.widgets.CListView', array(
                    'dataProvider' => $dataProvider,
                    'itemView'     => '_post',
                    'summaryText'  => false,
                ));*/ ?>
            <?php /* echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView'     => '_post',
            ]); */ ?>
            <?php if ($topicProvider->count) { $this->render('_watch', array('topicProvider' => $topicProvider)); } ?>
        </div>


    </div>
</div>
