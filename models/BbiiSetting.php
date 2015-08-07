<?php

namespace sourcetoad\bbii2\models;

use sourcetoad\bbii2\models\BbiiAR;
use sourcetoad\bbii2\models\_query\BbiiSettingQuery;

use Yii;

/**
 * This is the model class for table "bbii_setting".
 *
 * The followings are the available columns in table 'bbii_setting':
 * @property string $id
 * @property string $contact_email
 */
class BbiiSetting extends BbiiAR
{
    public static function find()
    {
        return new BbiiSettingQuery(get_called_class());
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiSetting the static model class
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
		//return 'bbii_setting';
		return '{{%bbii2_setting}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('contact_email', 'required'),
			array('contact_email', 'email'),
			array('contact_email', 'string', 'max' => 255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, contact_email', 'safe', 'on' => 'search'),
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
			'contact_email' => Yii::t('BbiiModule.bbii', 'Forum contact e-mail address'),
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
		$criteria->compare('contact_email',$this->contact_email,true);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}*/
}