<?php

use yii\helpers\Html;
use yii\jui\Sortable;
use yii\web\UrlManager;

/* @var $this SettingController */
/* @var $data BbiiForum (category) */
/* @var $forum[] BbiiForum */

$forumitems = array();
foreach($forum as $forumdata) {
    $forumitems['frm_'.$forumdata->id] = $this->render('_forum', array('forumdata' => $forumdata), true);
}
?>

<table style = "margin:0;" data-id="<?php echo $data->id; ?>">
<tbody class = "category">
    <tr>
        <td class = "name">
            <?php echo Html::encode($data->name); ?>
        </td>
        <td rowspan = "2" style = "width:140px;">
            <?php
                /*
                    echo Html::buttonInput(
                        Yii::t('BbiiModule.bbii', 'Edit'),
                        [
                        'onclick' => 'js:editCategory(' . $data->id . ', "' . Yii::t('BbiiModule.bbii','Edit category') . '", "' . \Yii::$app->urlManager->createAbsoluteUrl('forum/setting/getforum') .'")'
                            //'onclick' => 'function(){Sort(this,"' . \Yii::$app->urlManager->createAbsoluteUrl('forum/setting/ajaxsort') . '");}();'
                        ]
                    );
                */
                echo Html::a(
                    Yii::t('BbiiModule.bbii', 'Edit'),
                    \Yii::$app->urlManager->createAbsoluteUrl(['forum/setting/updateforum', 'id' => $data->id])
                );
            ?>
        </td>
    </tr>
    <tr>
        <td class = "header4">
            <?php echo Html::encode($data->subtitle); ?>
        </td>
    </tr>
</tbody>
<tr>
    <td colspan = "2">
    <?php 
        /*$this->widget('zii.widgets.jui.CJuiSortable', array(
            'id' => 'sortfrm' . $data->id,
            'items' => $forumitems,
            'htmlOptions' => array('style' => 'list-style:none;margin-top:1px;padding-right:0;'),
            'theme' => $this->module->juiTheme,
            'options' => array(
                'delay' => '100',
                'update' => 'js:function(){Sort(this,"' . \Yii::$app->urlManager->createAbsoluteUrl('setting/ajaxSort') . '");}',
            ),
        ));*/

        echo Sortable::widget([
            'clientOptions' => ['cursor' => 'move'],
            'id'            => 'sortfrm' . $data->id,
            'itemOptions'   => ['tag' => 'li'],
            'items'         => $forumitems,
            'options' => array(
                'delay'  => '100',
                'update' => 'js:function(){Sort(this,"' . \Yii::$app->urlManager->createAbsoluteUrl('setting/ajaxSort') . '");}',
            ),
        ]);
    ?>
    </td>
</tr>
</table>