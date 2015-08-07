<?php

namespace sourcetoad\bbii2\models;

use sourcetoad\bbii2\models\BbiiAR;
use sourcetoad\bbii2\models\_query\BbiiSpiderQuery;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "bbii_spider".
 *
 * The followings are the available columns in table 'bbii_spider':
 * @property integer $id
 * @property string $name
 * @property string $user_agent
 * @property integer $hits
 * @property string $last_visit
 */
class BbiiSpider extends BbiiAR
{
    public static function find()
    {
        return new BbiiSpiderQuery(get_called_class());
    }

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		//return 'bbii_spider';
 		return '{{%bbii2_spider}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			[['name', 'user_agent'], 'required'],
			['name', 'string', 'max' => 45],
			['user_agent', 'string', 'max' => 255],
			[['hits'], 'integer', 'min' => 0, 'max' => 99999999999],
			array('hits', 'default',  'value' => 0),
			array('last_visit', 'default', 'value' => 'NOW()', 'on' => 'visit'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.

			//array('id, name, user_agent, last_visit', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => Yii::t('BbiiModule.bbii', 'Webspider'),
			'user_agent' => 'User Agent',
			'last_visit' => Yii::t('BbiiModule.bbii', 'Last visit'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * @deprecated 2.1.5
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	/*public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('last_visit',$this->last_visit,true);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'last_visit DESC',
			),
			'pagination' => array(
				'pageSize' => 50,
			),
		));
	}*/

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * 
	 * @param  [type] $params [description]
	 * @return ActiveDataProvider The data provider that can return the models based on the search/filter conditions.
	 */
	public function search($params = null) {
		$query        = BbiiSpider::find();
		$dataProvider = new ActiveDataProvider([
			'pagination' => array('pageSize' => 50,),
			'query'      => $query,
			'sort'       => array('defaultOrder' => 'last_visit DESC',),
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('id',			$this->id,			true);
		$this->addCondition('last_visit',	$this->last_visit,	true);
		$this->addCondition('name',			$this->name,		true);
		$this->addCondition('user_agent',	$this->user_agent,	true);

	    return $dataProvider;
	}
	
	/**
	 * [scopes description]
	 *
	 * @deprecated 2.0.1
	 * @return [type] [description]
	 */
	public function scopes() {
		return true;
		$recent = date('Y-m-d H:i:s', time() - 900);
		return array(
			'present' => array(
				'order' => 'last_visit DESC',
				'condition' => "last_visit > '$recent'",
			),
		);
	}

	
	public function getUrl() {
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $this->user_agent, $match);
		if (isset($match[0][0])) {
			return $match[0][0];
		} else {
			return '';
		}
	}
}