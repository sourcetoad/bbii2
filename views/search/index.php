<?php
/* @var $this SearchController */
/* @var $dataProvider CActiveDataProvider */
/* @var $search String */
/* @var $choice Integer */
/* @var $type Integer */

$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum')=>array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Search'),
);

$approvals = BbiiPost::model()->unapproved()->count();
$reports = BbiiMessage::model()->report()->count();

$item = array(
	array('label'=>Yii::t('BbiiModule.bbii', 'Forum'), 'url'=>array('forum/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Members'), 'url'=>array('member/index')),
	array('label'=>Yii::t('BbiiModule.bbii', 'Approval'). ' (' . $approvals . ')', 'url'=>array('moderator/approval'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Reports'). ' (' . $reports . ')', 'url'=>array('moderator/report'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Posts'), 'url'=>array('moderator/admin'), 'visible'=>$this->isModerator()),
	array('label'=>Yii::t('BbiiModule.bbii', 'Blocked IP'), 'url'=>array('moderator/ipadmin'), 'visible'=>$this->isModerator()),
);
?>
<div id="bbii-wrapper">
	<?php echo $this->renderPartial('_header', array('item'=>$item)); ?>
	
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'bbii-search-form',
			'action'=>array('search/index'),
			'enableAjaxValidation'=>false,
	));
		echo CHtml::textField('search', $search, array('size'=>80,'maxlength'=>100));
		echo CHtml::submitButton(Yii::t('BbiiModule.bbii','Search')) . '<br>';
		echo CHtml::radioButtonList('choice', $choice, array('1'=>Yii::t('BbiiModule.bbii','Subject'), '2'=>Yii::t('BbiiModule.bbii','Content'), '0'=>Yii::t('BbiiModule.bbii','Both')), array('separator'=>'&nbsp;'));
		echo ' | ';
		echo CHtml::radioButtonList('type', $type, array('1'=>Yii::t('BbiiModule.bbii','Any word'), '2'=>Yii::t('BbiiModule.bbii','All words'), '0'=>Yii::t('BbiiModule.bbii','Phrase')), array('separator'=>'&nbsp;'));
	$this->endWidget();
	?>

	<?php $this->widget('zii.widgets.CListView', array(
		'id'=>'bbii-search-result',
		'dataProvider'=>$dataProvider,
		'itemView'=>'_post',
	)); ?>
	
	<?php echo $this->renderPartial('_footer'); ?>
</div>