<?php

namespace frontend\modules\bbii\models;

use frontend\modules\bbii\models\BbiiAR;
use frontend\modules\bbii\models\_query\BbiiForumQuery;

use yii;

/**
 * This is the model class for table "bbii_forum".
 *
 * The followings are the available columns in table 'bbii_forum':
 * @property string $id
 * @property string $cat_id
 * @property string $name
 * @property string $subtitle
 * @property integer $type
 * @property integer $public
 * @property integer $moderated
 * @property integer $locked
 * @property integer $sort
 * @property integer $num_posts
 * @property integer $num_topics
 * @property integer $last_post_id
 * @property integer $membergroup_id
 * @property integer $poll
 */
class BbiiForum extends BbiiAR
{
    public static function find()
    {
        return new BbiiForumQuery(get_called_class());
    }

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'bbii_forum';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		// propert 'last_post_time' has been removed - DJE : 2015-05-20
		return [
			[['name'], 'required'],
			[['type', 'public', 'locked', 'moderated', 'sort', 'num_posts', 'num_topics', 'last_post_id', 'membergroup_id', 'poll'], 'integer'],
			[['name'], 'unique'],
			[['name', 'subtitle'],  'string', 'min' => 4,  'max' => 255],
			[['cat_id'],  'string',  'max' => 10],
			[['type'], 'validateType'],
			[['cat_id', 'subtitle'], 'default',  'value' => null],
			[['public'], 'default',  'value' => 1],
			[['locked', 'membergroup_id', 'poll'], 'default',  'value' => 0],
			[['name', 'subtitle', 'type', 'sort', 'num_posts', 'num_topics', 'last_post_id', 'membergroup_id', 'poll'], 'safe'],

			[['id', 'cat_id', 'name', 'subtitle', 'type', 'sort', 'num_posts', 'num_topics', 'last_post_id', 'membergroup_id', 'poll'], 'safe',  'on' => 'search'],
		];
	}
	
	public function validateType($attr, $params) {
		if ($this->type == 0 && !empty($this->cat_id)) {
			$this->addError('cat_id', Yii::t('BbiiModule.bbii', 'A category cannot be assigned to a category.'));
		}
		if ($this->type == 1 && empty($this->cat_id)) {
			$this->addError('cat_id', Yii::t('BbiiModule.bbii', 'A forum needs to be assigned to a category.'));
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'lastPost' => array(self::BELONGS_TO, 'BbiiPost', 'last_post_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id'         => Yii::t('BbiiModule.bbii', 'Category'),
			'id'             => 'ID',
			'last_post_id'   => 'Last Post',
			'locked'         => Yii::t('BbiiModule.bbii', 'Locked'),
			'membergroup_id' => Yii::t('BbiiModule.bbii', 'For member group'),
			'moderated'      => Yii::t('BbiiModule.bbii', 'Moderated'),
			'name'           => Yii::t('BbiiModule.bbii', 'Name'),
			'num_posts'      => Yii::t('BbiiModule.bbii', 'posts'),
			'num_topics'     => Yii::t('BbiiModule.bbii', 'topics'),
			'poll'           => Yii::t('BbiiModule.bbii', 'Poll'),
			'public'         => Yii::t('BbiiModule.bbii', 'Public'),
			'sort'           => 'Sort',
			'subtitle'       => Yii::t('BbiiModule.bbii', 'Subtitle'),
			'type'           => Yii::t('BbiiModule.bbii', 'Type'),
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
		$criteria->compare('cat_id',$this->cat_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('subtitle',$this->subtitle,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('num_posts',$this->num_posts);
		$criteria->compare('num_topics',$this->num_topics);
		$criteria->compare('last_post_id',$this->last_post_id,true);
		$criteria->compare('poll',$this->poll,true);

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
	public function search($params){
		$query        = BbiiForum::find();
		$dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('cat_id',		$this->cat_id,		true);
		$this->addCondition('id',			$this->id,			true);
		$this->addCondition('last_post_id',	$this->last_post_id,true);
		$this->addCondition('name',			$this->name,		true);
		$this->addCondition('num_posts',	$this->num_posts);
		$this->addCondition('num_topics',	$this->num_topics);
		$this->addCondition('poll',			$this->poll,		true);
		$this->addCondition('sort',			$this->sort);
		$this->addCondition('subtitle',		$this->subtitle,	true);
		$this->addCondition('type',			$this->type);

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
			'categories' => array(
				'condition' => 'type = 0',
				'order' => 'sort',
			),
			'category' => array(
				'condition' => 'type = 0',
			),
			'forum' => array(
				'condition' => 'type = 1',
			),
			'public' => array(
				'condition' => 'public = 1',
			),
			'sorted' => array(
				'order' => 'sort',
			),
		);
	}
	
	public function membergroup($membergroup = 0) {
		$this->getDbCriteria()->mergeWith(array(
			'condition' => "(membergroup_id = 0 or membergroup_id = $membergroup)",
		));
		return $this;
	}
	
	/**
	 * 
	 * @version  2.1.1
	 * @return
	 */
	public static function getForumOptions() {
		$return = array();

		$groups = BbiiForum::find()->where('type = 0')->orderBy('sort')->all();
		foreach($groups as $group) {
			$forum = BbiiForum::find()->where('type = 1 and cat_id = ' . $group->id)->all(); 

			foreach($forum as $option) {
				if ($option->public || !Yii::$app->user->isGuest) {
					if ($option->membergroup_id == 0) {
						$return[] = array('id' => $option->id,'name' => $option->name,'group' => $group->name);
					} elseif (!Yii::$app->user->isGuest) {
						$groupId = BbiiMember::find(Yii::$app->user->id)->group_id;
						if ($option->membergroup_id == $groupId) {
							$return[] = array('id' => $option->id,'name' => $option->name,'group' => $group->name);
						}
					}
				}
			}
		}
		return $return;
	}
	
	public static function getAllForumOptions() {
		$return = array();
		// $criteria = new CDbCriteria;
		// $criteria->condition = 'type = 0';
		// $criteria->order = 'sort';
		// $category = BbiiForum::find()->findAll($criteria);
		$category = BbiiForum::find()->where('type = 0')->orderBy('sort')->all();
		foreach($category as $group) {
			//$criteria->condition = 'type = 1 and cat_id = ' . $group->id;
			//$forum = BbiiForum::find()->findAll($criteria);
			$forum = BbiiForum::find()->where('type = 1 and cat_id = ' . $group->id)->all();
			foreach($forum as $option) {
				$return[] = array('id' => $option->id,'name' => $option->name,'group' => $group->name);
			}
		}
		return $return;
	}
}