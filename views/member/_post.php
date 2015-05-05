<?php echo CHtml::link($data->subject, array(
		'forum/topic', 
		'id'=>$data->topic_id, 
		'nav'=>$data->id
	),
	array(
		'title'=>DateTimeCalculation::medium($data->create_time) . ': ' . $data->forum->name,
	)
); 
?><br>