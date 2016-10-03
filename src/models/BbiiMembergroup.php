<?php

namespace sourcetoad\bbii2\models;

use sourcetoad\bbii2\models\BbiiAR;
use sourcetoad\bbii2\models\_query\BbiiMembergroupQuery;

use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "bbii_membergroup".
 *
 * The followings are the available columns in table 'bbii_membergroup':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $min_posts
 * @property string $color
 * @property string $image
 */
class BbiiMembergroup extends BbiiAR
{
    public static function find()
    {
        return new BbiiMembergroupQuery(get_called_class());
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'bbii_membergroup';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name',     'required'),
            [['min_posts'], 'integer'],
            ['name', 'string', 'max' => 45],
            ['color', 'string', 'max' => 6],
            array('color',     'match', 'pattern' => '/[0-9a-fA-F]{6}/i'),
            ['image', 'string', 'max' => 255],
            array('image',     'default', 'value' => null),
            array('description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, min_posts, color, image', 'safe', 'on' => 'search'),
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
            'name' => 'Name',
            'description' => 'Description',
            'min_posts' => 'Min Posts',
            'color' => 'Color',
            'image' => 'Image',
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('min_posts',$this->min_posts);
        $criteria->compare('color',$this->color,true);
        $criteria->compare('image',$this->image,true);

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
        $query        = BbiiMembergroup::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition('color',        $this->color,        true);
        $this->addCondition('description',    $this->description,    true);
        $this->addCondition('id',            $this->id,            true);
        $this->addCondition('image',        $this->image,        true);
        $this->addCondition('min_posts',    $this->min_posts);
        $this->addCondition('name',            $this->name,        true);

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
        return array(
            'specific' => array(
                'condition' => 'id > 0',
            ),
        );
    }
}