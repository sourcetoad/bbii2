<?php
/* @var $this SettingController */
/* @var $forumdata BbiiForum (forum) */
?>

<table style="margin:0px;">
<tbody class="forum">
	<tr>
		<td class="name">
			<?= Html::encode($forumdata->name); ?>
		</td>
		<td rowspan="2" style="width:140px;">
			<?= Html::button(Yii::t('BbiiModule.bbii','Edit'), array('onclick' => 'editForum(' . $forumdata->id . ',"' . Yii::t('BbiiModule.bbii','Edit forum') . '", "' . $this->createAbsoluteUrl('setting/getForum') .'")')); ?>
			<?php if(!$forumdata->public) echo Html::img($this->module->getRegisteredImage('private.png'), 'private', array('style' => 'vertical-align:middle;', 'title' => 'Private')); ?>
			<?php if($forumdata->locked) echo Html::img($this->module->getRegisteredImage('locked.png'), 'locked', array('style' => 'vertical-align:middle;', 'title' => 'Locked')); ?>
			<?php if($forumdata->moderated) echo Html::img($this->module->getRegisteredImage('moderated.png'), 'moderated', array('style' => 'vertical-align:middle;', 'title' => 'Moderated')); ?>
		</td>
	</tr>
	<tr>
		<td class="header4">
			<?= Html::encode($forumdata->subtitle); ?>
		</td>
	</tr>
</tbody>
</table>