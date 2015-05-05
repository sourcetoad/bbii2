<?php

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
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
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
			array('source, count', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>39),
			array('ip', 'unique'),
			array('address', 'length', 'max'=>255),
			array('source, count','default','value'=>0, 'on'=>'insert'),
			array('create_time', 'default', 'value'=>new CDbExpression('NOW()'), 'on'=>'insert'),
			array('update_time', 'default', 'value'=>new CDbExpression('NOW()'), 'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, ip, address, source, count, create_time, update_time', 'safe', 'on'=>'search'),
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
		if(strlen($this->ip) > 0 and $this->address == '') {
			$this->address = gethostbyaddr($this->ip);
		}
		return parent::beforeValidate();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ip' => Yii::t('BbiiModule.bbii','IP address'),
			'address' => Yii::t('BbiiModule.bbii','Host name'),
			'source' => 'Source',
			'count' => Yii::t('BbiiModule.bbii','Count'),
			'create_time' => 'Create Time',
			'update_time' => Yii::t('BbiiModule.bbii','Last blocked'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('count',$this->count);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function blocked($ip) {
		$model = BbiiIpaddress::model()->find("ip = '$ip'");
		if($model === null) {
			return false;
		} else {
			$model->saveCounters(array('count'=>1));					// method since Yii 1.1.8
			return true;
		}
	}
}