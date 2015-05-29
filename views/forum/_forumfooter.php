<?php

use frontend\modules\bbii\models\BbiiForum;
use frontend\modules\bbii\models\BbiiMember;
use frontend\modules\bbii\models\BbiiMessage;
use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiSession;
use frontend\modules\bbii\models\BbiiTopic;

use yii\helpers\Html;

$member = BbiiMember::find()->newest()->one();

/* @var $this ForumController */
?>
<div id = "bbii-footer">
	<table><tr>
		<td class = "legend">
			<table>
				<caption>
					<?php echo Yii::t('BbiiModule.bbii','Forum legend'); ?>
				</caption>
				<tr>
					<td>
						<div class = "forum-cell topic1"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Unread topic'); ?>
					</td>
					<td>
						<div class = "forum-cell topic1s"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Sticky topic'); ?>
					</td>
					<td>
						<div class = "forum-cell topic1g"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Global topic'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<div class = "forum-cell topic2"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Read topic'); ?>
					</td>
					<td>
						<div class = "forum-cell topic1l"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Locked topic'); ?>
					</td>
					<?php // @todo Polls disabled for init release - DJE : 2015-05-29 ?>
					<?php /*
					<td>
						<div class = "forum-cell topic1p"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Poll'); ?>
					</td>
					*/?>
				</tr>
			</table>
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
				<th><?php echo Yii::t('BbiiModule.bbii','Newest member'); ?></th><td><?php echo Html::a($member->member_name, array('member/view', 'id' => $member->id)); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Visitors today'); ?></th><td><?php echo BbiiSession::find()->count(); ?></td>
			</tr>
			</table>
		</td>
	</tr></table>
</div>
