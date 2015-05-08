<?php
/* @var $this SearchController */
/* @var $data BbiiPost */
?>

<div class="post">
	<div class="fade">
		<div class="header2 pad5">
			<?= Html::a(Html::encode($data->subject), array('forum/topic', 'id'=>$data->topic_id, 'nav'=>$data->id)); ?>
		</div>
		<div class="header4">
			<?= '&nbsp;&raquo; ' . Html::encode($data->poster->member_name); ?>
			<?= ' &raquo; ' . DateTimeCalculation::full($data->create_time); ?>
			<?= Yii::t('BbiiModule.bbii','in'); ?>
			<?= Html::a($data->forum->name, array('forum/forum', 'id'=>$data->forum_id)); ?>
		</div>
	</div>
	<hr>
	<div class="margin5">
		<?= $this->getSearchedString($data->content, 10); ?>
	</div>
	<hr>
	<div class="margin5">
		<?= Html::a(Yii::t('BbiiModule.bbii','View Topic'), array('forum/topic', 'id'=>$data->topic_id)); ?>
	</div>
</div>