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
/* @var $breadcrumbs array */
?>
<div id="bbii-header">
	<?php if(!Yii::$app->user->isGuest) { ?>
		<div class="bbii-profile-box">
			<?php 
				if($messages) {
					echo Html::a(
						Html::img(
							$assets->baseUrl.'/images/newmail.png',
							array(
								'title'=>$messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages')
							)
						),
						array('message/inbox')
					); 
				} else {
					echo Html::a(
						Html::img(
							$assets->baseUrl.'/images/mail.png',
							array(
								'title'=>$messages . ' ' . Yii::t('BbiiModule.bbii', 'new messages')
							)
						),
						array('message/inbox')
					); 
				}
				echo ' | ';
				echo Html::a(
					Html::img(
						$assets->baseUrl.'/images/settings.png',
						array('title'=>Yii::t('BbiiModule.bbii', 'My settings'))
					),
					array('member/view', 'id' =>Yii::$app->user->id)
				); 
			?>
		</div>
	<?php }; ?>

	<div class="bbii-title"><?= $this->context->module->forumTitle; ?></div>

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
				        echo Nav::widget(['items' => $item]);
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

<?php /*if(isset($this->bbii_breadcrumbs)):?>
	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'homeLink'=>false,
		'links'=>$this->bbii_breadcrumbs,
	)); ?><!-- breadcrumbs -->
<?php endif;*/ ?>