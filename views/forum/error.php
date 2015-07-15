<?php
/* @var $this ForumController */
/* @var $error array */

$this->pageTitle = Yii::$app->name . ' - ' . Yii::t('BbiiModule.bbii', 'Error');

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Error'),
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index'))
);
?>

<div id = "bbii-wrapper">
	<?php echo $this->render('_header', array('item' => $item)); ?>

	<h2><?php echo Yii::t('BbiiModule.bbii', 'Error') . ' ' . $code; ?></h2>

	<div class = "error">
		<h4><?php echo Html::encode($message); ?></h4>
	</div>
</div>