<?php
/* @var $this ModeratorController */
/* @var $model BbiiPost */
/* @var $poll BbiiPoll */
/* @var $choices array */
?>
<table>
<tr>
	<th style="width:150px;"><?php echo CHtml::activeLabel($model, 'user_id'); ?></th>
	<td><?php echo CHtml::encode($model->poster->member_name); ?></td>
</tr>
<tr>
	<th><?php echo CHtml::activeLabel($model, 'subject'); ?></th>
	<td><?php echo CHtml::encode($model->subject); ?></td>
</tr>
<tr>
	<th><?php echo CHtml::activeLabel($model, 'create_time'); ?></th>
	<td><?php echo DateTimeCalculation::full($model->create_time); ?></td>
</tr>
<?php if($poll !== null): ?>
<tr>
	<th><?php echo Yii::t('BbiiModule.bbii', 'Poll'); ?></th>
	<td><?php echo CHtml::encode($poll->question); ?></td>
</tr>
<?php foreach($choices as $key => $choice): ?>
<tr>
	<th><?php echo Yii::t('BbiiModule.bbii', 'Question') . ' ' . ($key + 1); ?></th>
	<td><?php echo CHtml::encode($choice); ?></td>
</tr>

<?php endforeach; ?>
<?php endif; ?>
<tr>
	<td colspan="2"><hr><?php echo $model->content; ?></td>
</tr>
</table>