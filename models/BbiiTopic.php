<?php

/**
 * This is the model class for table "bbii_topic".
 *
 * The followings are the available columns in table 'bbii_topic':
 * @property string $id
 * @property string $forum_id
 * @property string $user_id
 * @property string $title
 * @property string $first_post_id
 * @property string $last_post_id
 * @property string $num_replies
 * @property string $num_views
 * @property integer $approved
 * @property integer $locked
 * @property integer $sticky
 * @property integer $global
 * @property integer $moved
 * @property integer $upvoted
 */
class BbiiTopic extends BbiiAR
{
	public $merge;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiTopic the static model class
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
		return 'bbii_topic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('forum_id, title, first_post_id, last_post_id', 'required'),
			array('forum_id, user_id, first_post_id, last_post_id, num_replies, num_views, moved, approved, locked, sticky, global, upvoted', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('user_id', 'default', 'value'=>Yii::$app->user->id, 'on'=>'insert'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, forum_id, user_id, title, first_post_id, last_post_id, num_replies, num_views, approved, locked, sticky, global, moved', 'safe', 'on'=>'search'),
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
			'forum' => array(self::BELONGS_TO, 'BbiiForum', 'forum_id'),
			'starter' => array(self::BELONGS_TO, 'BbiiMember', 'user_id'),
			'firstPost' => array(self::BELONGS_TO, 'BbiiPost', 'first_post_id'),
			'lastPost' => array(self::BELONGS_TO, 'BbiiPost', 'last_post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'forum_id' => Yii::t('BbiiModule.bbii', 'Forum'),
			'user_id' => 'User',
			'title' => Yii::t('BbiiModule.bbii', 'Title'),
			'first_post_id' => 'First Post',
			'last_post_id' => 'Last Post',
			'num_replies' => Yii::t('BbiiModule.bbii', 'replies'),
			'num_views' => Yii::t('BbiiModule.bbii', 'views'),
			'approved' => 'Approved',
			'locked' => Yii::t('BbiiModule.bbii', 'Locked'),
			'sticky' => Yii::t('BbiiModule.bbii', 'Sticky'),
			'global' => Yii::t('BbiiModule.bbii', 'Global'),
			'moved' => 'Moved',
			'merge' => Yii::t('BbiiModule.bbii', 'Merge with topic'),
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
		$criteria->compare('forum_id',$this->forum_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('first_post_id',$this->first_post_id,true);
		$criteria->compare('last_post_id',$this->last_post_id,true);
		$criteria->compare('num_replies',$this->num_replies,true);
		$criteria->compare('num_views',$this->num_views,true);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('locked',$this->locked);
		$criteria->compare('sticky',$this->sticky);
		$criteria->compare('global',$this->global);
		$criteria->compare('moved',$this->moved,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns the css class when a member has posted in a topic
	 */
	public function hasPostedClass() {
		if(!Yii::$app->user->isGuest && BbiiPost::model()->exists("topic_id = $this->id and user_id = ".Yii::$app->user->id)) {
			return 'posted';
		}
		return '';
	}
}