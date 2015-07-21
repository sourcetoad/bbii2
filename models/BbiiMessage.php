<?php

namespace frontend\modules\bbii\models;

use frontend\modules\bbii\models\BbiiAR;
use frontend\modules\bbii\models\_query\BbiiMessageQuery;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "bbii_message".
 *
 * The followings are the available columns in table 'bbii_message':
 * @property string $id
 * @property string $sendfrom
 * @property string $sendto
 * @property string $subject
 * @property string $content
 * @property string $create_time
 * @property integer $read_indicator
 * @property integer $type
 * @property integer $inbox
 * @property integer $outbox
 * @property string $ip
 * @property string $post_id
 */
class BbiiMessage extends BbiiAR
{
	public $search;

    public static function find()
    {
        return new BbiiMessageQuery(get_called_class());
    }

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'bbii_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			// @todo iterate on this rule - DJE : 2015-05-19

			// [['content'], 'filter', 'filter' => [$obj = new HtmlPurifier(), 'purify']],
			// [['create_time'], 'safe'],
			[['ip'], 'blocked'],
			[['ip'], 'string', 'max' => 39],
			[['sendfrom', 'sendto', 'read_indicator', 'type', 'inbox', 'outbox', 'post_id'], 'integer'],
			[['sendfrom', 'sendto', 'subject', 'content'], 'required'],
			[['subject'], 'string', 'max' => 255],
			[['sendfrom', 'sendto', 'subject', 'content'], 'safe'],

			// scenarios
			[['create_time'], 'default', 'value' => 'NOW()', 'on' => 'insert'],
			[['sendto'], 'mailboxFull', 'on' => 'insert'],
			[['id', 'sendfrom', 'sendto', 'subject', 'content', 'read_indicator', 'type', 'inbox', 'outbox', 'ip', 'post_id'], 'safe', 'on' => 'search'],
			[['ip'], 'default', 'value' => $_SERVER['REMOTE_ADDR'], 'on' => 'insert'],
		];
	}
	
	public function mailboxFull($attr, $params) {
		$criteria = new CDbCriteria;
		$criteria->condition = 'outbox = 1 and sendfrom = '. \Yii::$app->user->identity->id ;
		if (BbiiMessage::find()->outbox()->count($criteria) >= 50) {
			$this->addError('sendto', Yii::t('BbiiModule.bbii', 'Your outbox is full. Please make room before sending new messages.'));
		}
	}

	public function blocked($attribute, $params) {
		if (BbiiIpaddress::blocked($this->ip)) {
			$this->addError('ip', Yii::t('BbiiModule.bbii','Your IP address has been blocked.'));
		}
	}
	
	/**
	 * @deprecated 2.7.5
	 * @return array relational rules.
	 */
	/* public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'sender' => array(self::BELONGS_TO, 'BbiiMember', 'sendfrom'),
			'receiver' => array(self::BELONGS_TO, 'BbiiMember', 'sendto'),
			'forumPost' => array(self::BELONGS_TO, 'BbiiPost', 'post_id'),
		);
	} */

	/**
	 * @return array customized attribute labels (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'content'        => Yii::t('BbiiModule.bbii', 'Content'),
			'create_time'    => Yii::t('BbiiModule.bbii', 'Posted'),
			'id'             => 'ID',
			'inbox'          => Yii::t('BbiiModule.bbii', 'Inbox'),
			'ip'             => 'Ip',
			'outbox'         => Yii::t('BbiiModule.bbii', 'Outbox'),
			'read_indicator' => Yii::t('BbiiModule.bbii', 'Read'),
			'sendfrom'       => Yii::t('BbiiModule.bbii', 'From'),
			'sendto'         => Yii::t('BbiiModule.bbii', 'To'),
			'subject'        => Yii::t('BbiiModule.bbii', 'Subject'),
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
		$criteria->compare('sendfrom',$this->sendfrom,true);
		$criteria->compare('sendto',$this->sendto,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('read_indicator',$this->read_indicator);
		$criteria->compare('type',$this->type);
		$criteria->compare('inbox',$this->inbox);
		$criteria->compare('outbox',$this->outbox);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('post_id',$this->post_id,true);
		$criteria->limit = 100;

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => false,
			'sort' => array('defaultOrder' => 'id DESC'),
		));
	}*/

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * 
	 * @param  [type] $params [description]
	 * @return ActiveDataProvider The data provider that can return the models based on the search/filter conditions.
	 */
	public function search($params = null) {
		$query        = BbiiMessage::find();
		$dataProvider = new ActiveDataProvider([
			'pagination' => false,
			'query'      => $query,
			'sort'       => array('defaultOrder' => 'id DESC'),
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('content',			$this->content,			true);
		$this->addCondition('id',				$this->id,				true);
		$this->addCondition('inbox',			$this->inbox);
		$this->addCondition('ip',				$this->ip,				true);
		$this->addCondition('outbox',			$this->outbox);
		$this->addCondition('post_id',			$this->post_id,			true);
		$this->addCondition('read_indicator',	$this->read_indicator);
		$this->addCondition('sendfrom',			$this->sendfrom,		true);
		$this->addCondition('sendto',			$this->sendto,			true);
		$this->addCondition('subject',			$this->subject,			true);
		$this->addCondition('type',				$this->type);

		$criteria->limit = 100;

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
			'inbox' => array(
				'condition' => 'inbox = 1',
			),
			'outbox' => array(
				'condition' => 'outbox = 1',
			),
			'unread' => array(
				'condition' => 'read_indicator = 0',
			),
			'report' => array(
				'condition' => 'sendto = 0',
			),
		);
	}

    public function getSender()
    {

        return $this->hasOne(BbiiMember::className(), ['id' => 'sendfrom']);
    }

    public function getReciever()
    {

        return $this->hasOne(BbiiMember::className(), ['id' => 'sendto']);
    }

    public function getForumPost()
    {

        return $this->hasOne(BbiiPost::className(), ['id' => 'post_id']);
    }
}