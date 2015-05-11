<?php

namespace frontend\modules\bbii\components;

use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class SimpleSearchForm extends Widget{
	public $SimpleSearchForm;
	
	public function init(){
		parent::init();

		ActiveForm::begin([
			'action'               => array('search/index'),
			'enableAjaxValidation' => false,
			'id'                   => 'simple-search-form',
		]);
		echo Html::input('text', 'search', null, array('size' => 20,'maxlength' => 50,'class' => 'small-search-field'));
		echo Html::hiddenInput('choice','0');
		echo Html::hiddenInput('type','0');
		echo Html::submitButton(null, array('class' => 'small-search-button'));
		ActiveForm::end();

		return true;
	}
	
	public function run(){
		return $this->SimpleSearchForm;
	}
}
