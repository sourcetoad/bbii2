<?php

use Yii;
use yii\helpers\Html;

/* @var $this MessageController */
/* @var $model BbiiMessage */
/* @var $count Array */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Inbox'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Inbox') 	.' ('. $count['inbox'] .')', 	'url' => array('message/inbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'Outbox') 	.' ('. $count['outbox'] .')', 	'url' => array('message/outbox')),
	array('label' => Yii::t('BbiiModule.bbii', 'New message'), 								'url' => array('message/create'))
);
?>
<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>

	<h1><?php echo (\Yii::$app->requestedAction->id == 'create')?Yii::t('BbiiModule.bbii', 'New message'):Yii::t('BbiiModule.bbii', 'View Message'); ?></h1>

	<table>
		<thead>
			<tr>
				<th style = "width:150px;"><?php echo Html::activeLabel($model, 'sendfrom'); ?></th>
				<th><?php echo Html::encode($model->sendfrom); ?></th>
			</tr>
			<tr>
				<th><?php echo Html::activeLabel($model, 'sendto'); ?></th>
				<th><?php echo Html::encode($model->sendto); ?></th>
			</tr>
			<tr>
				<th><?php echo Html::activeLabel($model, 'subject'); ?></th>
				<th><?php echo Html::encode($model->subject); ?></th>
			</tr>
			<tr>
				<th><?php echo Html::activeLabel($model, 'create_time'); ?></th>
				<th><?php echo Html::encode($model->create_time); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th><?php echo Html::activeLabel($model, 'content'); ?></th>
				<th><?php echo Html::encode($model->content); ?></th>
			</tr>
		</tbody>
	</table>
</div>