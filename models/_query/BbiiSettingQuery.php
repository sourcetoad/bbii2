<?php

namespace frontend\modules\bbii\models\_query;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Created to replace Yii1's scope concept. Yii2 uses query classes
 *
 * @since  0.0.5
 */
class BbiiSettingQuery extends ActiveQuery
{
	public function find()
	{

		return $this;
	}

	public function findAll()
	{

		return $this;
	}

	// custom query methods

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * 
	 * @param  [type] $params [description]
	 * @return ActiveDataProvider The data provider that can return the models based on the search/filter conditions.
	 */
	public function search($params){
		$query        = BbiiSetting::find();
		$dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('id',				$this->id,				true);
		$this->addCondition('contact_email',	$this->contact_email,	true);

	    return $dataProvider;
	}
}
