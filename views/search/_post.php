<?php
/* @var $this SearchController */
/* @var $data BbiiPost */
?>

<div class="post">
	<div class="fade">
		<div class="header2 pad5">
			<?php echo CHtml::link(CHtml::encode($data->subject), array('forum/topic', 'id'=>$data->topic_id, 'nav'=>$data->id)); ?>
		</div>
		<div class="header4">
			<?php echo '&nbsp;&raquo; ' . CHtml::encode($data->poster->member_name); ?>
			<?php echo ' &raquo; ' . DateTimeCalculation::full($data->create_time); ?>
			<?php echo Yii::t('BbiiModule.bbii','in'); ?>
			<?php echo CHtml::link($data->forum->name, array('forum/forum', 'id'=>$data->forum_id)); ?>
		</div>
	</div>
	<hr>
	<div class="margin5">
		<?php echo $this->getSearchedString($data->content, 10); ?>
	</div>
	<hr>
	<div class="margin5">
		<?php echo CHtml::link(Yii::t('BbiiModule.bbii','View Topic'), array('forum/topic', 'id'=>$data->topic_id)); ?>
	</div>
</div>