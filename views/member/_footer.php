<?php
/* @var $this MemberController */
$present = BbiiSession::model()->present()->count();
$members = BbiiMember::model()->present()->count();
?>
<div id="bbii-footer">
	<table><tr>
		<td class="online">
			<div>
				<span class="header2"><?php echo Yii::t('BbiiModule.bbii','{guests} guest(s) and {members} active member(s)', array('{guests}'=>($present - $members), '{members}'=>$members));?></span>
				<?php echo Yii::t('BbiiModule.bbii','(in the past 15 minutes)');?>
			</div>
			<div>
				<?php $members = BbiiMember::model()->present()->findAll(); 
					foreach($members as $member) {
						echo CHtml::link($member->member_name, array('member/view', 'id'=>$member->id), array('style'=>'color:#'.$member->group->color)) . '&nbsp;';
					}
				?>
				<?php echo Yii::t('BbiiModule.bbii','({hidden} anonymous member(s))', array('{hidden}'=>BbiiMember::model()->hidden()->present()->count())); ?>
			</div>
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
