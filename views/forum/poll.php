<?php
/* @var $this ForumController */
/* @var $choiceProvider ActiveDataProvider */
$this->widget('zii.widgets.CListView', array(
	'id'=>'bbiiPoll',
	'dataProvider'=>$choiceProvider,
	'itemView'=>'_pollResult',
	'summaryText'=>false,
));
?>