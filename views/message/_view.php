<?php
/* @var $this MessageController */
/* @var $model BbiiMessage */
?>
<table>
<thead>
<tr>
	<th style="width:150px;"><?= Html::activeLabel($model, 'sendfrom'); ?></th>
	<th><?= Html::encode($model->sender->member_name); ?></th>
</tr>
<tr>
	<th><?= Html::activeLabel($model, 'sendto'); ?></th>
	<th><?= Html::encode($model->receiver->member_name); ?></th>
</tr>
<tr>
	<th><?= Html::activeLabel($model, 'subject'); ?></th>
	<th><?= Html::encode($model->subject); ?></th>
</tr>
<tr>
	<th><?= Html::activeLabel($model, 'create_time'); ?></th>
	<th><?= DateTimeCalculation::full($model->create_time); ?></th>
</tr>
</thead>
<tbody>
<tr>
	<td colspan="2"><?= $model->content; ?></td>
</tr>
</tbody>
</table>