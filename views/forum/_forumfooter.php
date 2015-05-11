<?php
/* @var $this ForumController */
?>
<div id="bbii-footer">
	<table><tr>
		<td class="legend">
			<table>
				<caption>
					<?= Yii::t('BbiiModule.bbii','Forum legend'); ?>
				</caption>
				<tr>
					<td>
						<div class="forum-cell topic1"></div>
					</td>
					<td>
						<?= Yii::t('BbiiModule.bbii','Unread topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1s"></div>
					</td>
					<td>
						<?= Yii::t('BbiiModule.bbii','Sticky topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1g"></div>
					</td>
					<td>
						<?= Yii::t('BbiiModule.bbii','Global topic'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<div class="forum-cell topic2"></div>
					</td>
					<td>
						<?= Yii::t('BbiiModule.bbii','Read topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1l"></div>
					</td>
					<td>
						<?= Yii::t('BbiiModule.bbii','Locked topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1p"></div>
					</td>
					<td>
						<?= Yii::t('BbiiModule.bbii','Poll'); ?>
					</td>
				</tr>
			</table>
		</td>
		<td class="statistics">
			<table>
			<caption class="header2">
				<?= Yii::t('BbiiModule.bbii','Board Statistics'); ?>
			</caption>
			<tr>
				<th><?= Yii::t('BbiiModule.bbii','Total topics'); ?></th><td><?= BbiiTopic::model()->count(); ?></td>
			</tr><tr>
				<th><?= Yii::t('BbiiModule.bbii','Total posts'); ?></th><td><?= BbiiPost::model()->count(); ?></td>
			</tr><tr>
				<th><?= Yii::t('BbiiModule.bbii','Total members'); ?></th><td><?= BbiiMember::model()->count(); ?></td>
			</tr><tr>
				<th><?= Yii::t('BbiiModule.bbii','Newest member'); ?></th><td><?php $member = BbiiMember::model()->newest()->find(); echo Html::a($member->member_name, array('member/view', 'id' => $member->id)); ?></td>
			</tr><tr>
				<th><?= Yii::t('BbiiModule.bbii','Visitors today'); ?></th><td><?= BbiiSession::model()->count(); ?></td>
			</tr>
			</table>
		</td>
	</tr></table>
</div>
