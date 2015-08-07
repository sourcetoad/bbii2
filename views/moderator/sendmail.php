<?php

use sourcetoad\bbii2\models\BbiiPost;
use sourcetoad\bbii2\models\BbiiMessage;

/* @var $this ModeratorController */
/* @var $model MailForm */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Send mail'),
);
?>

<div id = "bbii-wrapper" class="well clearfix">
	<?php echo $this->render('_header', array('item' => $item, '')); ?>

	<?php echo $this->render('_form', array('model' => $model)); ?>

</div>