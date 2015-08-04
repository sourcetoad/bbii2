<?php

use frontend\modules\bbii\models\BbiiMembergroup;

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\View;

use frontend\modules\bbii\AppAsset;
$assets = AppAsset::register($this);

/* @var $this ForumController */
/* @var $model BbiiMember */

$this->title = Yii::t('forum', 'Forum');
$this->params['breadcrumbs'][] = $this->title;

$this->context->bbii_breadcrumbs = array(
	Yii::t('BbiiModule.bbii', 'Forum') => array('forum/index'),
	Yii::t('BbiiModule.bbii', 'Members') => array('member/index'),
	$model->member_name . Yii::t('BbiiModule.bbii', '\'s profile'),
	Yii::t('BbiiModule.bbii', 'Update')
);

$item = array(
	array('label' => Yii::t('BbiiModule.bbii', 'Forum'),   'url' => array('forum/index')),
	array('label' => Yii::t('BbiiModule.bbii', 'Members'), 'url' => array('member/index'), 'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Approval'),'url' => array('moderator/approval'),   'visible' => $this->context->isModerator()),
	array('label' => Yii::t('BbiiModule.bbii', 'Posts'),   'url' => array('moderator/admin'),      'visible' => $this->context->isModerator()),
);

/*\Yii::$app->clientScript->registerScript('presence', "
$('.presence-button').click(function(){
	$('.presence').toggle();
	return false;
});
$('.presence').hide();
", CClientScript::POS_READY);*/
$script = <<< JS
$('.presence-button').click(function(){
	$('.presence').toggle();
	return false;
});

$('.presence').hide();
JS;
$this->registerJs($script, View::POS_READY);
?>
<div id="bbii-wrapper" class="well clearfix">
	<?php echo $this->render('_header', array('item' => $item)); ?>
	
    <div class="well clearix">

    	<div class = "bbii-box-top"><?php echo $model->member_name . Yii::t('BbiiModule.bbii', '\'s profile'); ?></div>

    	<div class = "form">

    		<?php // @depricated 2.4 Kept for referance
    		/*$form = $this->beginWidget('ActiveForm', array(
    			'enableAjaxValidation' => false,
    			'htmlOptions'          => array('enctype' => 'multipart/form-data'),
    			'id'                   => 'bbii-member-form',
    		));*/ ?>

    		<?php $form = ActiveForm::begin([
                'enableAjaxValidation'   => false,
                'enableClientValidation' => false,
                'id'                     => 'bbii-member-form',
                'options'                => array('enctype' => 'multipart/form-data'),
    		]); ?>
                <?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Update'), array('class' => 'btn btn-success btn-lg')); ?>

    			<?php //echo $form->errorSummary($model); ?>

    			<div>
                    <?php //echo $form->labelEx($model,'member_name'); ?>
                    <?php echo $form->field($model,'member_name')->textInput(array('size' => 45,'maxlength' => 45)); ?>
                    <?php //echo $form->error($model,'member_name'); ?>
    			</div>

    			<?php if ($this->context->isModerator()) { ?>
    				<div>
                        <?php //echo $form->labelEx($model,'group_id'); ?>
                        <?php //->label('Group Name') ?>
                        <?php echo $form->field($model, 'group_id')->dropDownList(
                            ArrayHelper::map(BbiiMembergroup::find()->all(), 'id', 'name'),
                            ['prompt' => 'Group', 'class' => 'form-control']
                        ); ?>
                        <?php //echo $form->error($model,'group_id'); ?>
    				</div>
                    <br />
    			<?php } ?>

    			<div>
                    <?php //echo $form->labelEx($model,'gender'); ?>
                    <?php /* echo Html::dropDownList(
                        $model,
                        'gender',
                        array(
                            '0' => Yii::t('BbiiModule.bbii', 'Male'),
                            '1' => Yii::t('BbiiModule.bbii', 'Female')
                        ),
                        ['prompt' => 'Gender', 'class' => 'form-control']
                    ); */ ?>
                    <?php //echo $form->error($model,'gender'); ?>
                    <?php echo $form->field($model, 'gender')->dropDownList(
                        ['0' => Yii::t('BbiiModule.bbii', 'Male'), '1' => Yii::t('BbiiModule.bbii', 'Female')],
                        ['prompt' => 'Choose', 'class' => 'form-control']
                    ); ?>
    			</div>
                <br />

    			<div>
                    <?php //echo $form->labelEx($model,'birthdate'); ?>
                    <?php //echo $form->field($model,'birthdate'); ?>
                    <?php // @todo iterate on this custom text field - DJE : 2015-05-19
                    /* echo DatePicker::widget(array(
                        // 'htmlOptions' => array(
                        // 	'style' => 'height:20px;'
                        // ),
                        'language' => substr(\Yii::$app->language, 0, 2),
                        'name'     => 'birthdate',
                        'options' => array(
                            'altField' => '#BbiiMember_birthdate',
                            'altFormat' => 'yy-mm-dd',
                            'showAnim' => 'fold',
                        ),
                        //'theme'    => $this->module->juiTheme,
                        'value'    => \Yii::$app->formatter->asDate($model->birthdate, 'short', null),
                    ));*/ ?>
                    <?php //echo $form->error($model,'birthdate'); ?>
    			</div>

    			<div>
                    <?php //echo $form->labelEx($model,'location'); ?>
                    <?php echo $form->field($model,'location')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                    <?php //echo $form->error($model,'location'); ?>
    			</div>

    			<div>
                    <?php //echo $form->labelEx($model,'personal_text'); ?>
                    <?php echo $form->field($model,'personal_text')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                    <?php //echo $form->error($model,'personal_text'); ?>
    			</div>

    			<div>
                    <?php //echo $form->labelEx($model,'show_online'); ?>
                    <?php /* echo Html::dropDownList(
                        $model,
                        'show_online',
                        array(
                            '0' => Yii::t('BbiiModule.bbii', 'No'),
                            '1' => Yii::t('BbiiModule.bbii', 'Yes')
                        ),
                        ['class' => 'form-control']
                    ); */ ?>
                    <?php //echo $form->error($model,'show_online'); ?>
                    <?php echo $form->field($model, 'show_online')->dropDownList(
                        ['0' => Yii::t('BbiiModule.bbii', 'No'), '1' => Yii::t('BbiiModule.bbii', 'Yes')],
                        ['prompt' => 'Choose', 'class' => 'form-control']
                    ); ?>
    			</div>
                <br />

    			<div>
                    <?php //echo $form->labelEx($model,'contact_email'); ?>
                    <?php /* echo Html::dropDownList(
                        $model,
                        'contact_email',
                        array(
                            '0' => Yii::t('BbiiModule.bbii', 'No'),
                            '1' => Yii::t('BbiiModule.bbii', 'Yes')
                        ),
                        ['class' => 'form-control']
                    ); */ ?>
                    <?php //echo $form->error($model,'contact_email'); ?>
                    <?php echo $form->field($model, 'contact_email')->dropDownList(
                        ['0' => Yii::t('BbiiModule.bbii', 'No'), '1' => Yii::t('BbiiModule.bbii', 'Yes')],
                        ['prompt' => 'Choose', 'class' => 'form-control']
                    ); ?>
    			</div>
                <br />

    			<div>
                    <?php //echo $form->labelEx($model,'contact_pm'); ?>
                    <?php /* echo Html::dropDownList(
                        $model,
                        'contact_pm',
                        array(
                            '0' => Yii::t('BbiiModule.bbii', 'No'),
                            '1' => Yii::t('BbiiModule.bbii', 'Yes')
                        ),
                        ['class' => 'form-control']
                    ); */ ?>
                    <?php //echo $form->error($model,'contact_pm'); ?>
                    <?php echo $form->field($model, 'contact_pm')->dropDownList(
                        ['0' => Yii::t('BbiiModule.bbii', 'No'), '1' => Yii::t('BbiiModule.bbii', 'Yes')],
                        ['prompt' => 'Choose', 'class' => 'form-control']
                    ); ?>
    			</div>
                <br />

    			<div>
                    <?php //echo $form->labelEx($model,'timezone'); ?>
                    <?php /* echo Html::dropDownList(
                        $model,
                        'timezone',
                        array_combine(
                            DateTimeZone::listIdentifiers(), DateTimeZone::listIdentifiers()
                        ),
                        ['class' => 'form-control']
                    ); */ ?>
                    <?php //echo $form->error($model,'timezone'); ?>
                    <?php echo $form->field($model, 'timezone')->dropDownList(
                        array_combine(DateTimeZone::listIdentifiers(), DateTimeZone::listIdentifiers()),
                        ['prompt' => 'Choose', 'class' => 'form-control']
                    ); ?>
    			</div>
                <br />

    			<div>
                    <?php //echo $form->labelEx($model,'avatar'); ?>
                    <?php //echo Html::img((isset($model->avatar))?(\Yii::$app->request->baseUrl . $this->module->avatarStorage . '/'. $model->avatar):$assets->baseUrl.'/images/empty.jpeg', 'avatar', array('align' => 'left','style' => 'margin:0 10px 10px 0;')); ?>
                    <?php //echo $form->labelEx($model,'remove_avatar'); ?>
                    <?php
                    echo Html::img(
                        (
                            !$model->getAttribute('avatar')
                            ? $assets->baseUrl.'/images/empty.jpeg'
                            : $assets->baseUrl.'/avatars/'. $model->getAttribute('avatar')
                        ),
                        [
                            'alt'   => 'avatar',
                            'class' => 'img-responsive img-circle',
                            'title' => 'avatar',

                        ]
                    ); ?>
                    <?php echo Html::activeCheckbox($model, 'remove_avatar'); ?>
                    <?php //echo $form->labelEx($model, 'image'); ?>
                    <?php echo $form->field($model, 'image')->fileInput(['class' => 'form-control', 'size' => 90]); ?>
                    <?php echo Yii::t('BbiiModule.bbii', 'Large images will be resized to fit a size of 90 pixels by 90 pixels.'); ?>
                    <?php //echo $form->error($model, 'image'); ?>
                </div>
                <br />

                <div>
                    <?php //echo $form->labelEx($model,'signature'); ?>
                    <?php // @todo iterate on this custom text field - DJE : 2015-05-19
                    /*$this->widget($this->module->id.'.extensions.editMe.widgets.ExtEditMe', array(
                        'model' => $model,
                        'attribute' => 'signature',
                        'autoLanguage' => false,
                        'height' => '120px',
                        'toolbar' => array(
                            array(
                                'Bold', 'Italic', 'Underline', 'RemoveFormat'
                            ),
                            array(
                                    'TextColor', 'BGColor',
                            ),
                            '-',
                            array('Link', 'Unlink', 'Image'),
                            '-',
                            array('Blockquote'),
                        ),
                        'skin' => $this->module->editorSkin,
                        'uiColor' => $this->module->editorUIColor,
                        'contentsCss' => $this->module->editorContentsCss,
                    ));*/ ?>
                    <?php //echo $form->error($model,'signature'); ?>

                    <?php echo $form->field($model, 'signature')->textArea(['class' => 'form-control']); ?>
                </div>



                    <div class="presence">
                        <?php //echo $form->labelEx($model,'website'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Globe.png', array('name' => 'Website')); ?>
                        <?php echo $form->field($model,'website')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'website'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'blogger'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Blogger.png', array('name' => 'Blogger')); ?>
                        <?php echo $form->field($model,'blogger')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'blogger'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'facebook'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Facebook.png', array('name' => 'Facebook')); ?>
                        <?php echo $form->field($model,'facebook')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'facebook'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'flickr'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Flickr.png', array('name' => 'Flickr')); ?>
                        <?php echo $form->field($model,'flickr')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'flickr'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'google'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Google.png', array('name' => 'Google')); ?>
                        <?php echo $form->field($model,'google')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'google'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'linkedin'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Linkedin.png', array('name' => 'Linkedin')); ?>
                        <?php echo $form->field($model,'linkedin')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'linkedin'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'metacafe'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Metacafe.png', array('name' => 'Metacafe')); ?>
                        <?php echo $form->field($model,'metacafe')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'metacafe'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'myspace'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Myspace.png', array('name' => 'Myspace')); ?>
                        <?php echo $form->field($model,'myspace')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'myspace'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'orkut'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Orkut.png', array('name' => 'Orkut')); ?>
                        <?php echo $form->field($model,'orkut')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'orkut'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'tumblr'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Tumblr.png', array('name' => 'Tumblr')); ?>
                        <?php echo $form->field($model,'tumblr')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'tumblr'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'twitter'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Twitter.png', array('name' => 'Twitter')); ?>
                        <?php echo $form->field($model,'twitter')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'twitter'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'wordpress'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Wordpress.png', array('name' => 'Wordpress')); ?>
                        <?php echo $form->field($model,'wordpress')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'wordpress'); ?>
                    </div>

                    <div class="presence">
                        <?php //echo $form->labelEx($model,'youtube'); ?>
                        <?php echo Html::img($assets->baseUrl.'/images/Youtube.png', array('name' => 'Youtube')); ?>
                        <?php echo $form->field($model,'youtube')->textInput(array('size' => 100,'maxlength' => 255)); ?>
                        <?php //echo $form->error($model,'youtube'); ?>
                    </div>



    			<div class = "buttons">
                    <?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Show social media options'), array('class' => 'btn btn-success btn-lg presence-button')); ?>
                    <?php echo Html::submitButton(Yii::t('BbiiModule.bbii', 'Update'), array('class' => 'btn btn-success btn-lg')); ?>
    			</div>

    		<?php ActiveForm::end(); ?>

    	</div><!-- form -->
    </div>
</div>
