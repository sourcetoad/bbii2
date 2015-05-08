<?php
/* @var $this ModeratorController */
/* @var $model BbiiPost */
/* @var $poll BbiiPoll */
/* @var $choices array */
?>
<table>
<tr>
	<th style="width:150px;"><?= Html::activeLabel($model, 'user_id'); ?></th>
	<td><?= Html::encode($model->poster->member_name); ?></td>
</tr>
<tr>
	<th><?= Html::activeLabel($model, 'subject'); ?></th>
	<td><?= Html::encode($model->subject); ?></td>
</tr>
<tr>
	<th><?= Html::activeLabel($model, 'create_time'); ?></th>
	<td><?= DateTimeCalculation::full($model->create_time); ?></td>
</tr>
<?php if($poll !== null): ?>
<tr>
	<th><?= Yii::t('BbiiModule.bbii', 'Poll'); ?></th>
	<td><?= Html::encode($poll->question); ?></td>
</tr>
<?php foreach($choices as $key => $choice): ?>
<tr>
	<th><?= Yii::t('BbiiModule.bbii', 'Question') . ' ' . ($key + 1); ?></th>
	<td><?= Html::encode($choice); ?></td>
</tr>

<?php endforeach; ?>
<?php endif; ?>
<tr>
	<td colspan="2"><hr><?= $model->content; ?></td>
</tr>
</table>