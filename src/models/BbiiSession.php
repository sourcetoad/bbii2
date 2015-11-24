<?php

namespace sourcetoad\bbii2\models;

use sourcetoad\bbii2\models\BbiiAR;
use sourcetoad\bbii2\models\_query\BbiiSessionQuery;

/**
 * This is the model class for table "bbii_session".
 *
 * The followings are the available columns in table 'bbii_session':
 * @property string $id
 * @property string $last_visit
 */
class BbiiSession extends BbiiAR
{
    public static function find()
    {
        return new BbiiSessionQuery(get_called_class());
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
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
        return [
            [['id'], 'required'],
            ['id', 'string', 'max' => 128],
            [['last_visit'], 'safe'],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            [['id, last_visit'], 'safe', 'on' => 'search'],
        ];
    }
    
    public function beforeSave($insert) {
        // @todo not sure why 'NOW()' is not working - DJE : 2015-05-29
        // $this->last_visit = date('Y-m-d H:m:i');
        return parent::beforeSave($insert);
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
            'last_visit' => 'Last Visit',
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
        $criteria->compare('last_visit',$this->last_visit,true);

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
        $query        = BbiiSession::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition('id',            $this->id,            true);
        $this->addCondition('last_visit',    $this->last_visit,    true);

        return $dataProvider;
    }
    
    /**
     * [scopes description]
     *
     * @deprecated 2.0.1
     * @return [type] [description]
     */
    public function scopes() {
        return true;
        $recent = date('Y-m-d H:i:s', time() - 900);
        return array(
            'present' => array(
                'condition' => "last_visit > '$recent'",
            ),
        );
    }
}