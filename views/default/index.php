<?php

use yii\helpers\Html;

/* @var $this ForumController */
/* @var $dataProvider CArrayDataProvider */

$this->title = Yii::t('BbiiModule.bbii', 'Forum');
$this->params['breadcrumbs'][] = ['label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="bbii-wrapper">
	<?php
	echo $this->render(
		'_header', array(
			'approvals' => $approvals,
			'messages'  => $messages,
			'reports'   => $reports,
		)
	);

	/*$this->widget('zii.widgets.CListView', array(
		'dataProvider' => $dataProvider,
		'id'           => 'bbiiForum',
		'itemView'     => '_forum',
		'summaryText'  => false,
		'viewData'     => array('lastIndex'=>($dataProvider->totalItemCount - 1)),
	));*/ //echo $this->renderPartial('_footer'); 

	if (!Yii::$app->user->isGuest) {
		echo Html::a(
			Yii::t('BbiiModule.bbii','Mark all read'),
			array('forum/markAllRead')
		);
	}

	?>
	<div id="bbii-copyright">

	</div>
</div>