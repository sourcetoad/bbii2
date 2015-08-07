<?php

namespace sourcetoad\bbii2\models;

use sourcetoad\bbii2\models\BbiiAR;

/**
 * This is the model class for table "bbii_question".
 *
 * The followings are the available columns in table 'bbii_question':
 * @property string $id
 * @property string $question
 * @property string $poll_id
 * @property integer $sort
 * @property integer $votes
 */
class BbiiQuestion extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiQuestion the static model class
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
		//return 'bbii_question';
		return '{{%bbii2_question}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question, poll_id', 'required'),
			[['sort, votes', 'numerical'], 'integer'],
			['question', 'string', 'max' => 200],
			['poll_id', 'string', 'max' => 10],
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, question, poll_id, sort, votes', 'safe', 'on' => 'search'),
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
			'question' => 'Question',
			'poll_id' => 'Poll',
			'sort' => 'Sort',
			'votes' => 'Votes',
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
		$criteria->compare('question',$this->question,true);
		$criteria->compare('poll_id',$this->poll_id,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('votes',$this->votes);

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
		$query        = BbiiQuestion::find();
		$dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('id',		$this->id,			true);
		$this->addCondition('poll_id',	$this->poll_id,		true);
		$this->addCondition('question',	$this->question,	true);
		$this->addCondition('sort',		$this->sort);
		$this->addCondition('votes',	$this->votes);

	    return $dataProvider;
	}
}