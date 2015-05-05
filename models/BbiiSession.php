<?php

/**
 * This is the model class for table "bbii_session".
 *
 * The followings are the available columns in table 'bbii_session':
 * @property string $id
 * @property string $last_visit
 */
class BbiiSession extends BbiiAR
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiSession the static model class
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
		return 'bbii_session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'length', 'max'=>128),
			array('last_visit', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, last_visit', 'safe', 'on'=>'search'),
		);
	}
	
	public function beforeSave() {
		$this->last_visit = new CDbExpression('NOW()');
		return parent::beforeSave();
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'last_visit' => 'Last Visit',
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
		$criteria->compare('last_visit',$this->last_visit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function scopes() {
		$recent = date('Y-m-d H:i:s', time() - 900);
		return array(
			'present' => array(
				'condition' => "last_visit > '$recent'",
			),
		);
	}
}