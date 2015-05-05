<?php
/* @var $this ForumController */
/* @var $choiceProvider CActiveDataProvider */
echo CHtml::form('', 'post', array('id'=>'bbii-poll-form'));
echo CHtml::hiddenField('poll_id', $this->poll->id);
$this->widget('zii.widgets.CListView', array(
	'id'=>'bbiiPoll',
	'dataProvider'=>$choiceProvider,
	'itemView'=>'_pollChoice',
	'summaryText'=>false,
));
echo '<div style="text-align:right;width:50%">';
echo CHtml::button(Yii::t('bbii', 'Vote'), array('onclick'=>'vote("' . $this->createAbsoluteUrl('forum/vote') . '");'));
echo '</div>';
echo CHtml::endForm();
?>