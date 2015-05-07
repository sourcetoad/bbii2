<?php

namespace frontend\modules\bbii\components;

use yii\base\Widget;
use yii\helpers\Html;

class SimpleSearchForm extends Widget {

	public function init() {
		parent::init();
	}

	public function run() {
		$this->renderContent();
	}

    protected function renderContent() {
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'simple-search-form',
			'action'=>array('search/index'),
			'enableAjaxValidation'=>false,
		));
			echo CHtml::textField('search','', array('size'=>20,'maxlength'=>50,'class'=>'small-search-field'));
			echo CHtml::hiddenField('choice','0');
			echo CHtml::hiddenField('type','0');
			echo CHtml::submitButton('',array('class'=>'small-search-button'));
		$this->endWidget();
    }
}