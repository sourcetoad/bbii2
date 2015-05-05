<?php

use yii\helpers\Html;

/* @var $this ForumController */
/* @var $dataProvider CArrayDataProvider */

/*$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum'),
);*/

?>
<div id="bbii-wrapper">
	<?php
	echo $this->render(
		'_header', array(
			'item'     => $item,
			'messages' => $messages,
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
		<a href="http://www.yiiframework.com/extension/bbii/" target="_blank">
			&copy; 2013-<?php echo date('Y'); ?><?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->context->module->version; ?> by BBii forum software
		</a>
	</div>
</div>