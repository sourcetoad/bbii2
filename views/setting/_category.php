<?php
/* @var $this SettingController */
/* @var $data BbiiForum (category) */
/* @var $forum[] BbiiForum */

$forumitems = array();
foreach($forum as $forumdata) {
	$forumitems['frm_'.$forumdata->id] = $this->renderPartial('_forum', array('forumdata'=>$forumdata), true);
}
?>

<table style="margin:0;">
<tbody class="category">
	<tr>
		<td class="name">
			<?php echo CHtml::encode($data->name); ?>
		</td>
		<td rowspan="2" style="width:140px;">
			<?php echo CHtml::button(Yii::t('BbiiModule.bbii','Edit'), array('onclick'=>'editCategory(' . $data->id . ',"' . Yii::t('BbiiModule.bbii','Edit category') . '", "' . $this->createAbsoluteUrl('setting/getForum') .'")')); ?>
		</td>
	</tr>
	<tr>
		<td class="header4">
			<?php echo CHtml::encode($data->subtitle); ?>
		</td>
	</tr>
</tbody>
<tr>
	<td colspan="2">
	<?php 
		$this->widget('zii.widgets.jui.CJuiSortable', array(
			'id' => 'sortfrm' . $data->id,
			'items' => $forumitems,
			'htmlOptions'=>array('style'=>'list-style:none;margin-top:1px;padding-right:0;'),
			'theme'=>$this->module->juiTheme,
			'options'=>array(
				'delay'=>'100',
				'update'=>'js:function(){Sort(this,"' . $this->createAbsoluteUrl('setting/ajaxSort') . '");}',
			),
		));
	?>
	</td>
</tr>
</table>