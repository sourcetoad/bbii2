<?php
/* @var $this ForumController */
?>
<div id="bbii-footer">
	<div class="row">
		<div class="online">
			<div>
				<span class="title"><?php echo Yii::t('bbii','{number} active member(s)', array('{number}'=>BbiiMember::model()->present()->count()));?></span>
				<?php echo Yii::t('bbii','(in the past 15 minutes)');?>
			</div>
			<div>
				<?php $members = BbiiMember::model()->present()->findAll(); 
					foreach($members as $member) {
						echo CHtml::link($member->member_name, array('member/view', 'id'=>$member->id));
					}
				?>
				<?php echo Yii::t('bbii','(and {hidden} anonymous member(s))', array('{hidden}'=>BbiiMember::model()->hidden()->present()->count())); ?>
			</div>
		</div>
		<div class="statistics">
			<div class="title">
				<?php echo Yii::t('bbii','Board Statistics'); ?>
			</div>
			<div class="row">
				<span class="header"><?php echo Yii::t('bbii','Total topics'); ?></span> <?php echo BbiiTopic::model()->count(); ?>
			</div>
			<div class="row">
				<span class="header"><?php echo Yii::t('bbii','Total posts'); ?></span> <?php echo BbiiPost::model()->count(); ?>
			</div>
			<div class="row">
				<span class="header"><?php echo Yii::t('bbii','Total members'); ?></span> <?php echo BbiiMember::model()->count(); ?>
			</div>
			<div class="row">
				<span class="header"><?php echo Yii::t('bbii','Newest member'); ?></span> <?php $member = BbiiMember::model()->newest()->find(); echo CHtml::link($member->member_name, array('member/view', 'id'=>$member->id)); ?>
			</div>
		</div>
	</div>
</div>
