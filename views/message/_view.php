<?php
/* @var $this MessageController */
/* @var $model BbiiMessage */
?>
<table>
<thead>
<tr>
	<th style="width:150px;"><?php echo Html::activeLabel($model, 'sendfrom'); ?></th>
	<th><?php echo Html::encode($model->sender->member_name); ?></th>
</tr>
<tr>
	<th><?php echo Html::activeLabel($model, 'sendto'); ?></th>
	<th><?php echo Html::encode($model->receiver->member_name); ?></th>
</tr>
<tr>
	<th><?php echo Html::activeLabel($model, 'subject'); ?></th>
	<th><?php echo Html::encode($model->subject); ?></th>
</tr>
<tr>
	<th><?php echo Html::activeLabel($model, 'create_time'); ?></th>
	<th><?php echo DateTimeCalculation::full($model->create_time); ?></th>
</tr>
</thead>
<tbody>
<tr>
	<td colspan="2"><?php echo $model->content; ?></td>
</tr>
</tbody>
</table>