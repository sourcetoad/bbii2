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
	<table><tr>
		<td class = "online">
			<div>
				<span class = "header2"><?php echo Yii::t('BbiiModule.bbii','{0} guest(s) and {1} active member(s)', array(
					($present - $members) > 0 ?: 0,
					$members)
				);?></span>
				<?php echo Yii::t('BbiiModule.bbii','(in the past 15 minutes)');?>
			</div>
			<div>
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
		</td>
		<td class = "statistics">
			<table>
			<caption class = "header2">
				<?php echo Yii::t('BbiiModule.bbii','Board Statistics'); ?>
			</caption>
			<tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Total topics'); ?></th><td><?php echo BbiiTopic::find()->count(); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Total posts'); ?></th><td><?php echo BbiiPost::find()->count(); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Total members'); ?></th><td><?php echo BbiiMember::find()->count(); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Newest member'); ?></th>
				<td>
					<?php 
					if ($newest->member_name != NULL) {
						echo Html::a($newest->member_name, array('member/view', 'id' => $newest->id));
					}
					?>
				</td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Visitors today'); ?></th><td><?php echo BbiiSession::find()->count(); ?></td>
			</tr>
			</table>
		</td>
	</tr></table>
    </div>
</div>
