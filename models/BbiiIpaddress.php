<?php

namespace frontend\modules\bbii\models;

use frontend\modules\bbii\models\BbiiAR;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "bbii_ipaddress".
 *
 * The followings are the available columns in table 'bbii_ipaddress':
 * @property string $id
 * @property string $ip
 * @property string $address
 * @property integer $source
 * @property integer $count
 * @property string $create_time
 * @property string $update_time
 */
class BbiiIpaddress extends BbiiAR
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiIpaddress the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'bbii_ipaddress';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			[['source, count', 'numerical'], 'integer'],
			['ip', 'string', 'max' => 39],
			array('ip', 'unique'),
			['address', 'string', 'max' => 255],
			array('source, count','default','value' => 0, 'on' => 'insert'),
			array('create_time', 'default', 'value' => 'NOW()', 'on' => 'insert'),
			array('update_time', 'default', 'value' => 'NOW()', 'on' => 'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ip, address, source, count, create_time, update_time', 'safe', 'on' => 'search'),
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

	public function beforeValidate() {
		if (strlen($this->ip) > 0 and $this->address == '') {
			$this->address = gethostbyaddr($this->ip);
		}
		return parent::beforeValidate();
	}

	/**
	 * @return array customized attribute labels (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'address'     => Yii::t('BbiiModule.bbii','Host name'),
			'count'       => Yii::t('BbiiModule.bbii','Count'),
			'create_time' => 'Create Time',
			'id'          => 'ID',
			'ip'          => Yii::t('BbiiModule.bbii','IP address'),
			'source'      => 'Source',
			'update_time' => Yii::t('BbiiModule.bbii','Last blocked'),
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
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('count',$this->count);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}*/

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * 
	 * @param  [type] $params [description]
	 * @return ActiveDataProvider The data provider that can return the models based on the search/filter conditions.
	 */
	public function search($params = null) {
		$query        = BbiiIpaddress::find();
		$dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('address',		$this->address,		true);
		$this->addCondition('count',		$this->count);
		$this->addCondition('create_time',	$this->create_time,	true);
		$this->addCondition('id',			$this->id,			true);
		$this->addCondition('ip',			$this->ip,			true);
		$this->addCondition('source',		$this->source);
		$this->addCondition('update_time',	$this->update_time,	true);

	    return $dataProvider;
	}

	public static function blocked($ip) {
		$model = BbiiIpaddress::find()->find("ip = '$ip'");
		if ($model === null) {
			return false;
		} else {
			$model->updateCounters(array('count' => 1));					// method since Yii 1.1.8
			return true;
		}
	}
}