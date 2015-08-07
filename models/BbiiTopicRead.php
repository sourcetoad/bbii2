<?php

namespace sourcetoad\bbii2\models;

use sourcetoad\bbii2\models\BbiiAR;

/**
 * This is the model class for table "bbii_topic_read".
 *
 * The followings are the available columns in table 'bbii_topic_read':
 * @property string $user_id
 * @property string $data
 */
class BbiiTopicRead extends BbiiAR
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BbiiTopicRead the static model class
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
        //return 'bbii_topic_read';
        return '{{%bbii2_topic_read}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            [['user_id', 'data'], 'required'],
            //['user_id', 'integer', 'max' => ],
            [['user_id'], 'integer', 'min' => 0, 'max' => 99999999999],

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [['user_id', 'data'], 'safe'],
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
            'user_id' => 'User',
            'data' => 'Data',
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

        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('data',$this->data,true);

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
        $query        = BbiiTopicRead::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition('data',      $this->data,    true);
        $this->addCondition('user_id',   $this->user_id, true);

        return $dataProvider;
    }
} 