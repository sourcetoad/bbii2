<?php
/* @var $this ForumController */
?>
<div id="bbii-footer">
	<div class="row">
		<div class="online">
			<div>
				<span class="title"><?= Yii::t('bbii','{number} active member(s)', array('{number}' => BbiiMember::find()->present()->count()));?></span>
				<?= Yii::t('bbii','(in the past 15 minutes)');?>
			</div>
			<div>
				<?php $members = BbiiMember::find()->present()->findAll(); 
					foreach($members as $member) {
						echo Html::a($member->member_name, array('member/view', 'id' => $member->id));
					}
				?>
				<?= Yii::t('bbii','(and {hidden} anonymous member(s))', array('{hidden}' => BbiiMember::find()->hidden()->present()->count())); ?>
			</div>
		</div>
		<div class="statistics">
			<div class="title">
				<?= Yii::t('bbii','Board Statistics'); ?>
			</div>
			<div class="row">
				<span class="header"><?= Yii::t('bbii','Total topics'); ?></span> <?= BbiiTopic::find()->count(); ?>
			</div>
			<div class="row">
				<span class="header"><?= Yii::t('bbii','Total posts'); ?></span> <?= BbiiPost::find()->count(); ?>
			</div>
			<div class="row">
				<span class="header"><?= Yii::t('bbii','Total members'); ?></span> <?= BbiiMember::find()->count(); ?>
			</div>
			<div class="row">
				<span class="header"><?= Yii::t('bbii','Newest member'); ?></span> <?php $member = BbiiMember::find()->newest()->find(); echo Html::a($member->member_name, array('member/view', 'id' => $member->id)); ?>
			</div>
		</div>
	</div>
</div>
