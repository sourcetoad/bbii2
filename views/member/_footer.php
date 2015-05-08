<?php

use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiSession;
use frontend\modules\bbii\models\BbiiTopic;
use frontend\modules\bbii\models\BbiiPost;

use yii\helpers\Html;

/* @var $this ForumController */
$member_count  = BbiiMember::find()->count();
$member_newest = BbiiMember::find()->newest()->find();
$members       = BbiiMember::find()->all();

$present       = BbiiSession::find()->count();
?>
<div id="bbii-footer">
	<table>
		<tr>
			<td class="online">
				<div>
					<span class="header2">
						<?= Yii::t('BbiiModule.bbii','{0} guest(s) and {1} active member(s)', array(
							($present - $member_count), $member_count
						));?>
					</span>
					<?= Yii::t('BbiiModule.bbii','(in the past 15 minutes)');?>
				</div>
				<div>
					<?php foreach($members as $member) {
							echo Html::a(
								$member->member_name, array(
									'member/view', 'id'=>$member->id),
								array('style'=>'color:#'.$member->group->color
							)) . '&nbsp;';
					}; ?>
					<?= Yii::t(
						'BbiiModule.bbii','({0} anonymous member(s))',
						array(BbiiMember::find()->hidden()->present()->count()
					)); ?>
				</div>
			</td>
			<td class="statistics">
				<table>
					<caption class="header2">
						<?= Yii::t('BbiiModule.bbii','Board Statistics'); ?>
					</caption>
					<tr>
						<th><?= Yii::t('BbiiModule.bbii','Total topics'); ?></th>
						<td><?= BbiiTopic::find()->count(); ?></td>
					</tr><tr>
						<th><?= Yii::t('BbiiModule.bbii','Total posts'); ?></th>
						<td><?= BbiiPost::find()->count(); ?></td>
					</tr><tr>
						<th><?= Yii::t('BbiiModule.bbii','Total members'); ?></th>
						<td><?= BbiiMember::find()->count(); ?></td>
					</tr><tr>
						<th><?php //echo Yii::t('BbiiModule.bbii','Newest member'); ?></th>
						<td><?php //echo Html::a($member_newest, array('member/view', 'id'=>$member_newest->id)); ?></td>
					</tr><tr>
						<th><?= Yii::t('BbiiModule.bbii','Visitors today'); ?></th>
						<td><?= BbiiSession::find()->count(); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
