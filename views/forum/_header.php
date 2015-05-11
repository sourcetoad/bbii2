<?php

use frontend\modules\bbii\AppAsset;
use frontend\modules\bbii\components\SimpleSearchForm;
use frontend\modules\bbii\models\BbiiForum;

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $item array */

?>
<div id="bbii-header">
	<?php if(!Yii::$app->user->isGuest) { ?>
		<div class="bbii-profile-box">
			<?php 
				if ($messages) {
					echo Html::a(
						Html::img(
							$assets->baseUrl.'/images/newmail.png',
							array('title' => $messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages'))
						),
						array('message/inbox')
					); 
				} else {
					echo Html::a(
						Html::img(
							$assets->baseUrl.'/images/mail.png',
							array('title' => Yii::t('BbiiModule.bbii', 'no new messages'))
						),
						array('message/inbox')
					); 
				}

				echo ' | ';
				echo Html::a(
					Html::img(
						$assets->baseUrl.'/images/settings.png',
						array('title' => Yii::t('BbiiModule.bbii', 'My settings'))
						),
					array('member/view', 'id'  => Yii::$app->user->id)
				); 

				if ($is_mod) {
					echo ' | ';
					echo Html::a(
						Html::img(
							$assets->baseUrl.'/images/moderator.png',
							array('title' => Yii::t('BbiiModule.bbii', 'Moderate'))),
						array('moderator/approval')
					);
				}

				if ($is_admin) {
					echo ' | ';
					echo Html::a(
						Html::img(
							$assets->baseUrl.'/images/config.png',
							array('title' => Yii::t('BbiiModule.bbii', 'Forum settings'))),
						array('setting/index')
					);
				}
			?>
		</div>
	<?php }; ?>

	<div class="bbii-title">
		<?= $this->context->module->forumTitle; ?>
	</div>

	<table>
		<tr>
			<td>
				<div id="bbii-menu">
					<?php
				        NavBar::begin([
				            'options' => [
					            	'class' => 'nav nav-pills'
				            ],
				        ]);
				        echo Nav::widget([
				        	'items' => [
								array('label' => Yii::t('BbiiModule.bbii', 'Forum'), 									'url' => array('forum/index')),
								array('label' => Yii::t('BbiiModule.bbii', 'Members'), 									'url' => array('member/index')),
								array('label' => Yii::t('BbiiModule.bbii', 'Approval'). ' (' . count($approvals) . ')', 'url' => array('moderator/approval')),
								array('label' => Yii::t('BbiiModule.bbii', 'Reports').  ' (' . count($reports) . ')', 	'url' => array('moderator/report'),		'visible' => (Yii::$app->session->get('user.status') > 60 ? true : false)),
							]
				        ]);
				        NavBar::end();
					?>
				</div>
			</td>
			<td>
				<div class="pull-right">
					<?= SimpleSearchForm::widget(); ?>
				</div>
			</td>
		</tr>
	</table>
</div>

<?php
	echo Html::dropDownList(
		'bbii-jumpto',
		'',
		BbiiForum::getForumOptions(), 
		array(
			'empty'		 =>  Yii::t('BbiiModule.bbii','Select forum'),
			'onchange'	 =>  "window.location.href='" . Url::toRoute(array('forum')) . "/id/'+$(this).val()",
		)
	);
?>

<?php
/*$this->widget('zii.widgets.CBreadcrumbs', array(
	'homeLink' => false,
	'links'    => $this->bbii_breadcrumbs,
));*/
?><!-- breadcrumbs -->
