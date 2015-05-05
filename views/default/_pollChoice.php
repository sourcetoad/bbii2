<?php
/* @var $this ForumController */
/* @var $data BbiiChoice */
?>
<div class="poll">
	<?php if($this->poll->allow_multiple): ?>
		<?php echo CHtml::checkBox('choice['.$data->id.']', false, array('value'=>$data->id)); ?>
	<?php else: ?>
		<?php echo CHtml::radioButton('choice[]', false, array('value'=>$data->id)); ?>
	<?php endif;?>
	<?php echo $data->choice; ?>
</div>