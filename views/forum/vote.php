<?php
/* @var $this ForumController */
/* @var $choiceProvider CActiveDataProvider */
echo Html::form('', 'post', array('id' => 'bbii-poll-form'));
echo Html::hiddenField('poll_id', $this->poll->id);
$this->widget('zii.widgets.CListView', array(
	'id' => 'bbiiPoll',
	'dataProvider' => $choiceProvider,
	'itemView' => '_pollChoice',
	'summaryText' => false,
));
echo '<div style = "text-align:right;width:50%">';
echo Html::button(Yii::t('bbii', 'Vote'), array('onclick' => 'vote("' . $this->createAbsoluteUrl('forum/vote') . '");'));
echo '</div>';
echo Html::endForm();
?>