<?php

namespace frontend\modules\bbii\models;

use frontend\modules\bbii\models\BbiiAR;

/**
 * This is the model class for table "bbii_vote".
 *
 * The followings are the available columns in table 'bbii_vote':
 * @property string $poll_id
 * @property string $choice_id
 * @property string $user_id
 */
class BbiiVote extends BbiiAR
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BbiiVote the static model class
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
        return 'bbii_vote';
    }

    public function primaryKey() {
        return array('poll_id', 'choice_id', 'user_id');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('poll_id, choice_id, user_id', 'required'),
            ['poll_id, choice_id, user_id', 'string', 'max' => 10],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('poll_id, choice_id, user_id', 'safe', 'on' => 'search'),
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
            'poll_id' => 'Poll',
            'choice_id' => 'Choice',
            'user_id' => 'User',
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

        $criteria->compare('poll_id',$this->poll_id,true);
        $criteria->compare('choice_id',$this->choice_id,true);
        $criteria->compare('user_id',$this->user_id,true);

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
        $query        = BbiiUpvoted::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition('choice_id',    $this->choice_id,    true);
        $this->addCondition('poll_id',    $this->poll_id,            true);
        $this->addCondition('user_id',    $this->user_id,            true);

        return $dataProvider;
    }
}