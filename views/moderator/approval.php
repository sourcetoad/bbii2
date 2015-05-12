<?php

use frontend\modules\bbii\models\BbiiPost;
use frontend\modules\bbii\models\BbiiMessage;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\grid\ActionColumn;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ModeratorController */
/* @var $model BbiiPost */

/*
$this->bbii_breadcrumbs=array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Approval'),
);
*/

$approvals = BbiiPost::find()->unapproved()->count();
$moderator = ($this->context->id === 'moderator' ? true :  false);
$reports   = BbiiMessage::find()->report()->count();

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 		'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 		'url' => array('member/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'). 	' (' . $approvals . ')', 				'url' => array('moderator/approval'), 	'visible' => $moderator),
	array('label' => Yii::t('BbiiModule.bbii', 'Reports'). 		' (' . $reports . ')', 					'url' => array('moderator/report'), 	'visible' => $moderator),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'), 		'url' => array('moderator/admin'), 		'visible' => $moderator),
	array('label' => Yii::t('BbiiModule.bbii', 'Blocked IP'), 	'url' => array('moderator/ipadmin'),	'visible' => $moderator),
	array('label' => Yii::t('BbiiModule.bbii', 'Send mail'), 	'url' => array('moderator/sendmail'), 	'visible' => $moderator),
);
?>
<div id="bbii-wrapper">
	<?php echo $this->render('_header', array(
		'item' => $item
	));

	/*echo GridView::widget([
	    'dataProvider' => $dataProvider,
	    'columns' => [
			//'id'           => 'approval-grid',
			//'dataProvider' => $dataProvider,
			'columns'      => array(
				array(
					'name' => 'user_id',
					'value' => '$data->poster->member_name'
				),
				'subject',
				'ip',
				'create_time',
				array(
					'class'    => 'ActionColumn',
					'template' => '{view}{approve}{delete}',
					'buttons'  => array(
						'view' => array(
							'click'    => 'js:function() { viewPost($(this).attr("href"), "' . ('moderator/view') .'");return false; }',
							'imageUrl' => $assets->baseUrl.'/images/view.png',
							'url'      => '$data->id',
						),
						'approve' => array(
							'imageUrl' => $assets->baseUrl.'/images/approve.png',
							'label'    => Yii::t('BbiiModule.bbii','Approve'),
							'options'  => array('style' => 'margin-left:5px;'),
							'url'      => 'array("approve", "id" => $data->id)',
						),
						'delete' => array(
							'imageUrl' => $assets->baseUrl.'/images/delete.png',
							'options'  => array('style' => 'margin-left:5px;'),
						),
					)
				),
			),
	        ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
	    ],
	]);*/
	?>

	<div id="bbii-message"></div>

</div>