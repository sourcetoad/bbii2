<?php

/**
 * This is the model class for table "bbii_poll".
 *
 * The followings are the available columns in table 'bbii_poll':
 * @property string $id
 * @property string $question
 * @property string $post_id
 * @property string $user_id
 * @property string $expire_date
 * @property integer $allow_revote
 * @property integer $allow_multiple
 * @property integer $votes
 */
class BbiiPoll extends BbiiAR
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiPoll the static model class
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
		return 'bbii_poll';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question, post_id, user_id', 'required'),
			array('allow_revote, allow_multiple, votes', 'numerical', 'integerOnly'=>true),
			array('question', 'length', 'max'=>200),
			array('post_id, user_id', 'length', 'max'=>10),
			array('expire_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, question, post_id, user_id, expire_date, allow_revote, allow_multiple, votes', 'safe', 'on'=>'search'),
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
			'question' => Yii::t('BbiiModule.bbii', 'Question'),
			'post_id' => 'Post',
			'user_id' => 'User',
			'expire_date' => 'Expire Date',
			'allow_revote' => 'Allow Revote',
			'allow_multiple' => 'Allow Multiple Choices',
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
		$criteria->compare('question',$this->question,true);
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('expire_date',$this->expire_date,true);
		$criteria->compare('allow_revote',$this->allow_revote);
		$criteria->compare('allow_multiple',$this->allow_multiple);
		$criteria->compare('votes',$this->votes);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}