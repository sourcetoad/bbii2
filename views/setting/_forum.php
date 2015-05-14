<?php

use yii\helpers\Html;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this SettingController */
/* @var $forumdata BbiiForum (forum) */
?>

<table style = "margin:0px;">
<tbody class = "forum">
	<tr>
		<td class = "name">
			<?php echo Html::encode($forumdata->name); ?>
		</td>
		<td rowspan = "2" style = "width:140px;">
			<?php echo Html::button(Yii::t('BbiiModule.bbii','Edit'), array('onclick' => 'editForum(' . $forumdata->id . ',"' . Yii::t('BbiiModule.bbii','Edit forum') . '", "' . $this->createAbsoluteUrl('setting/getForum') .'")')); ?>
			<?php if(!$forumdata->public) echo Html::img($asset->baseUrl.'private.png'), 'private', array('style' => 'vertical-align:middle;', 'title' => 'Private')); ?>
			<?php if($forumdata->locked) echo Html::img($asset->baseUrl.'locked.png'), 'locked', array('style' => 'vertical-align:middle;', 'title' => 'Locked')); ?>
			<?php if($forumdata->moderated) echo Html::img($asset->baseUrl.'moderated.png'), 'moderated', array('style' => 'vertical-align:middle;', 'title' => 'Moderated')); ?>
		</td>
	</tr>
	<tr>
		<td class = "header4">
			<?php echo Html::encode($forumdata->subtitle); ?>
		</td>
	</tr>
</tbody>
</table>