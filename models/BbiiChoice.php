<?php

/**
 * This is the model class for table "bbii_choice".
 *
 * The followings are the available columns in table 'bbii_choice':
 * @property string $id
 * @property string $choice
 * @property string $poll_id
 * @property integer $sort
 * @property integer $votes
 */
class BbiiChoice extends BbiiAR
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiChoice the static model class
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
		return 'bbii_choice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('choice, poll_id', 'required'),
			array('sort, votes', 'numerical', 'integerOnly'=>true),
			array('choice', 'length', 'max'=>200),
			array('poll_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, choice, poll_id, sort, votes', 'safe', 'on'=>'search'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'choice' => 'Choice',
			'poll_id' => 'Poll',
			'sort' => 'Sort',
			'votes' => 'Votes',
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
		$criteria->compare('choice',$this->choice,true);
		$criteria->compare('poll_id',$this->poll_id,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('votes',$this->votes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}