<?php

namespace frontend\modules\bbii\models;

use frontend\modules\bbii\models\BbiiAR;

/**
 * This is the model class for table "bbii_upvoted".
 *
 * The followings are the available columns in table 'bbii_upvoted':
 * @property string $member_id
 * @property string $post_id
 */
class BbiiUpvoted extends BbiiAR
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BbiiUpvoted the static model class
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
        return 'bbii_upvoted';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('member_id, post_id', 'required'),
            ['member_id, post_id', 'string', 'max' => 10],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('member_id, post_id', 'safe', 'on' => 'search'),
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
            'member_id' => 'Member',
            'post_id' => 'Post',
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

        $criteria->compare('member_id',$this->member_id,true);
        $criteria->compare('post_id',$this->post_id,true);

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

        $this->addCondition('member_id',$this->member_id,    true);
        $this->addCondition('post_id',    $this->post_id,        true);

        return $dataProvider;
    }
}