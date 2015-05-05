<?php
/* @var $this ForumController */
?>
<div id="bbii-footer">
	<table><tr>
		<td class="legend">
			<table>
				<caption>
					<?php echo Yii::t('BbiiModule.bbii','Forum legend'); ?>
				</caption>
				<tr>
					<td>
						<div class="forum-cell topic1"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Unread topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1s"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Sticky topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1g"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Global topic'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<div class="forum-cell topic2"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Read topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1l"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Locked topic'); ?>
					</td>
					<td>
						<div class="forum-cell topic1p"></div>
					</td>
					<td>
						<?php echo Yii::t('BbiiModule.bbii','Poll'); ?>
					</td>
				</tr>
			</table>
		</td>
		<td class="statistics">
			<table>
			<caption class="header2">
				<?php echo Yii::t('BbiiModule.bbii','Board Statistics'); ?>
			</caption>
			<tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Total topics'); ?></th><td><?php echo BbiiTopic::model()->count(); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Total posts'); ?></th><td><?php echo BbiiPost::model()->count(); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Total members'); ?></th><td><?php echo BbiiMember::model()->count(); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Newest member'); ?></th><td><?php $member = BbiiMember::model()->newest()->find(); echo CHtml::link($member->member_name, array('member/view', 'id'=>$member->id)); ?></td>
			</tr><tr>
				<th><?php echo Yii::t('BbiiModule.bbii','Visitors today'); ?></th><td><?php echo BbiiSession::model()->count(); ?></td>
			</tr>
			</table>
		</td>
	</tr></table>
</div>
