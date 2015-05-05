<?php
/* @var $this ForumController */
/* @var $choiceProvider CActiveDataProvider */
$this->widget('zii.widgets.CListView', array(
	'id'=>'bbiiPoll',
	'dataProvider'=>$choiceProvider,
	'itemView'=>'_pollResult',
	'summaryText'=>false,
));
?>