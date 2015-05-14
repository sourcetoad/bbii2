<?php

namespace frontend\modules\bbii\components;

Yii::import('zii.widgets.CPortlet');
 
class SimpleSearchForm extends CPortlet {
    protected function renderContent() {
		$form = $this->beginWidget('CActiveForm', array(
			'id' => 'simple-search-form',
			'action' => array('search/index'),
			'enableAjaxValidation' => false,
		));
			echo Html::textField('search','', array('size' => 20,'maxlength' => 50,'class' => 'small-search-field'));
			echo Html::hiddenField('choice','0');
			echo Html::hiddenField('type','0');
			echo Html::submitButton('',array('class' => 'small-search-button'));
		$this->endWidget();
    }
}