<?php

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
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiMembergroup the static model class
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
			array('name', 'required'),
			array('min_posts', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('color', 'length', 'max'=>6),
			array('color', 'match', 'pattern'=>'/[0-9a-fA-F]{6}/i'),
			array('image', 'length', 'max'=>255),
			array('image', 'default', 'value'=>null),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, min_posts, color, image', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'description' => 'Description',
			'min_posts' => 'Min Posts',
			'color' => 'Color',
			'image' => 'Image',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('min_posts',$this->min_posts);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('image',$this->image,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function scopes() {
		return array(
			'specific' => array(
				'condition' => 'id > 0',
			),
		);
	}
}