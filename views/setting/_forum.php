<?php
/* @var $this SettingController */
/* @var $forumdata BbiiForum (forum) */
?>

<table style="margin:0px;">
<tbody class="forum">
	<tr>
		<td class="name">
			<?php echo CHtml::encode($forumdata->name); ?>
		</td>
		<td rowspan="2" style="width:140px;">
			<?php echo CHtml::button(Yii::t('BbiiModule.bbii','Edit'), array('onclick'=>'editForum(' . $forumdata->id . ',"' . Yii::t('BbiiModule.bbii','Edit forum') . '", "' . $this->createAbsoluteUrl('setting/getForum') .'")')); ?>
			<?php if(!$forumdata->public) echo CHtml::image($this->module->getRegisteredImage('private.png'), 'private', array('style'=>'vertical-align:middle;', 'title'=>'Private')); ?>
			<?php if($forumdata->locked) echo CHtml::image($this->module->getRegisteredImage('locked.png'), 'locked', array('style'=>'vertical-align:middle;', 'title'=>'Locked')); ?>
			<?php if($forumdata->moderated) echo CHtml::image($this->module->getRegisteredImage('moderated.png'), 'moderated', array('style'=>'vertical-align:middle;', 'title'=>'Moderated')); ?>
		</td>
	</tr>
	<tr>
		<td class="header4">
			<?php echo CHtml::encode($forumdata->subtitle); ?>
		</td>
	</tr>
</tbody>
</table>