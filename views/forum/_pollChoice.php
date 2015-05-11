<?php
/* @var $this ForumController */
/* @var $data BbiiChoice */
?>
<div class="poll">
	<?php if($this->poll->allow_multiple): ?>
		<?= Html::checkBox('choice['.$data->id.']', false, array('value' => $data->id)); ?>
	<?php else: ?>
		<?= Html::radioButton('choice[]', false, array('value' => $data->id)); ?>
	<?php endif;?>
	<?= $data->choice; ?>
</div>