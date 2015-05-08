<?php

use yii\helpers\Html;
use yii\widgets\ListView;

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
		'summaryText'  => false,
		'viewData'     => array('lastIndex'=>($dataProvider->totalItemCount - 1)),
	]);

	if (!Yii::$app->user->isGuest) {
		echo Html::a(
			Yii::t('BbiiModule.bbii', 'Mark all read'),
			array('forum/markAllRead')
		);
	}

	echo $this->render('_footer');

	if(!Yii::$app->user->isGuest) {
		echo Html::a(
			Yii::t('BbiiModule.bbii','Mark all read'), array('forum/markAllRead')
		);
	};
	?>
	<div id="bbii-copyright">
		<a href="http://www.yiiframework.com/extension/bbii/" target="_blank" title="&copy; 2013-<?php echo date('Y'); ?>
			<?php echo Yii::t('BbiiModule.bbii','version') . ' ' . $this->context->module->version; ?>">BBii forum software
		</a>
	</div>
</div>