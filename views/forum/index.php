<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this ForumController */
/* @var $dataProvider CArrayDataProvider */

?>
<div id="bbii-wrapper">
	<?php 
	echo $this->render(
		'_header', array(
			'approvals' => $approvals,
			'is_admin'  => $is_admin,
			'is_mod'    => $is_mod,
			'messages'  => $messages,
			'reports'   => $reports,
		)
	);

	echo ListView::widget([
		'dataProvider' => $dataProvider,
		'id'           => 'bbiiForum',
		'itemView'     => '_forum',
		//'viewData'     => array('lastIndex' => ($dataProvider->getTotalCount() - 1) ),
	]);

	if (!Yii::$app->user->isGuest) {
		echo Html::a(
			Yii::t('BbiiModule.bbii', 'Mark all read'),
			array('forum/markAllRead')
		);
	}
	?>

	<?= $this->render('_footer'); ?>

	<?php
	if(!Yii::$app->user->isGuest) {
		echo Html::a(
			Yii::t('BbiiModule.bbii','Mark all read'), array('forum/markAllRead')
		);
	};
	?>
	<div id="bbii-copyright">
		<a href="http://www.yiiframework.com/extension/bbii/" target="_blank" title="&copy; 2013-<?= date('Y'); ?>
			<?= Yii::t('BbiiModule.bbii','version') . ' ' . $this->context->module->version; ?>">BBii forum software
		</a>
	</div>
</div>