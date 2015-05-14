<?php

namespace frontend\modules\bbii\models;

use frontend\modules\bbii\models\BbiiAR;
use frontend\modules\bbii\models\_query\BbiiMemberQuery;

use yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "bbii_member".
 *
 * The followings are the available columns in table 'bbii_member':
 * @property integer $id
 * @property string $member_name
 * @property integer $gender
 * @property string $birthdate
 * @property string $location
 * @property string $personal_text
 * @property string $signature
 * @property string $avatar
 * @property integer $show_online
 * @property integer $contact_email
 * @property integer $contact_pm
 * @property string $timezone
 * @property string $first_visit
 * @property string $last_visit
 * @property integer $warning
 * @property integer $posts
 * @property integer $group_id
 * @property integer $upvoted
 * @property string $blogger
 * @property string $facebook
 * @property string $flickr
 * @property string $google
 * @property string $linkedin
 * @property string $metacafe
 * @property string $myspace
 * @property string $orkut
 * @property string $tumblr
 * @property string $twitter
 * @property string $website
 * @property string $wordpress
 * @property string $yahoo
 * @property string $youtube
 * @property string $moderator
 */
class BbiiMember extends BbiiAR {
	public $image;
	public $remove_avatar;

    public static function find()
    {
        return new BbiiMemberQuery(get_called_class());
    }

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'bbii_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, member_name', 'required'),
			array('posts, group_id, upvoted', 'numerical', 'integerOnly' => true),
			array('gender, show_online, contact_email, contact_pm, warning, moderator', 'numerical', 'integerOnly' => true, 'max' => 1),
			array('member_name', 'unique', 'on' => 'update'),
			array('member_name', 'length', 'max' => 45),
			array('image', 'file', 'allowEmpty' => true,'maxSize' => 1025000, 'types' => 'gif, jpg, jpeg, png'),
			array('location, personal_text, avatar, blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube', 'length', 'max' => 255),
			array('timezone', 'length', 'max' => 80),
			array('gender, birthdate, location, personal_text, signature, avatar', 'default', 'value' => null),
			array('blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube', 'url'),
			array('blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube', 'default', 'value' => null),
			array('timezone', 'default', 'value' => 'Europe/London'),
			array('signature','filter','filter' => array($obj = new HtmlPurifier(), 'purify')),
			array('birthdate, signature, first_visit, last_visit, remove_avatar', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_name, gender, birthdate, location, personal_text, signature, avatar, show_online, contact_email, contact_pm, timezone, first_visit, last_visit, warning, posts, group_id, upvoted, blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube, moderator', 'safe', 'on' => 'search'),
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
			'group' => array(self::BELONGS_TO, 'BbiiMembergroup', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name => label)
	 */
	public function attributeLabels()
	{
		return array(
			'avatar'        => 'Avatar',
			'birthdate'     => Yii::t('BbiiModule.bbii', 'Birthdate'),
			'blogger'       => Yii::t('BbiiModule.bbii', 'My Blogger blog'),
			'contact_email' => Yii::t('BbiiModule.bbii', 'Allow members to contact you by e-mail'),
			'contact_pm'    => Yii::t('BbiiModule.bbii', 'Allow members to contact you by private messaging'),
			'facebook'      => Yii::t('BbiiModule.bbii', 'My Facebook page'),
			'first_visit'   => 'First Visit',
			'flickr'        => Yii::t('BbiiModule.bbii', 'My Flickr account'),
			'gender'        => Yii::t('BbiiModule.bbii', 'Gender'),
			'google'        => Yii::t('BbiiModule.bbii', 'My Google+ page'),
			'group_id'      => Yii::t('BbiiModule.bbii', 'Group'),
			'id'            => 'ID',
			'image'         => Yii::t('BbiiModule.bbii', 'Upload a new avatar'),
			'last_visit'    => 'Last Visit',
			'linkedin'      => Yii::t('BbiiModule.bbii', 'My Linkedin page'),
			'location'      => Yii::t('BbiiModule.bbii', 'Location'),
			'member_name'   => Yii::t('BbiiModule.bbii', 'Name to display'),
			'metacafe'      => Yii::t('BbiiModule.bbii', 'My Metacafe channel'),
			'moderator'     => Yii::t('BbiiModule.bbii', 'Moderator'),
			'myspace'       => Yii::t('BbiiModule.bbii', 'My Myspace page'),
			'orkut'         => Yii::t('BbiiModule.bbii', 'My Orkut page'),
			'personal_text' => Yii::t('BbiiModule.bbii', 'Profile text'),
			'posts'         => 'Posts',
			'remove_avatar' => Yii::t('BbiiModule.bbii', 'Remove your avatar'),
			'show_online'   => Yii::t('BbiiModule.bbii', 'Show when online'),
			'signature'     => Yii::t('BbiiModule.bbii', 'Signature'),
			'timezone'      => Yii::t('BbiiModule.bbii', 'Time zone'),
			'tumblr'        => Yii::t('BbiiModule.bbii', 'My Tumblr blog'),
			'twitter'       => Yii::t('BbiiModule.bbii', 'My Twitter page'),
			'upvoted'       => 'Upvoted',
			'warning'       => 'Warning',
			'website'       => Yii::t('BbiiModule.bbii', 'My website'),
			'wordpress'     => Yii::t('BbiiModule.bbii', 'My Wordpress blog'),
			'yahoo'         => Yii::t('BbiiModule.bbii', 'Yahoo'),
			'youtube'       => Yii::t('BbiiModule.bbii', 'My Youtube channel'),
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

		$criteria->compare('avatar',		$this->avatar,			true);
		$criteria->compare('birthdate',		$this->birthdate,		true);
		$criteria->compare('blogger',		$this->blogger,			true);
		$criteria->compare('contact_email',	$this->contact_email);
		$criteria->compare('contact_pm',	$this->contact_pm);
		$criteria->compare('facebook',		$this->facebook,		true);
		$criteria->compare('first_visit',	$this->first_visit,		true);
		$criteria->compare('flickr',		$this->flickr,			true);
		$criteria->compare('gender',		$this->gender);
		$criteria->compare('google',		$this->google,			true);
		$criteria->compare('group_id',		$this->group_id);
		$criteria->compare('id',			$this->id, 				true);
		$criteria->compare('last_visit',	$this->last_visit,		true);
		$criteria->compare('linkedin',		$this->linkedin,		true);
		$criteria->compare('location',		$this->location,		true);
		$criteria->compare('member_name',	$this->member_name,		true);
		$criteria->compare('metacafe',		$this->metacafe,		true);
		$criteria->compare('moderator',		$this->moderator,		true);
		$criteria->compare('myspace',		$this->myspace,			true);
		$criteria->compare('orkut',			$this->orkut,			true);
		$criteria->compare('personal_text',	$this->personal_text,	true);
		$criteria->compare('posts',			$this->posts);
		$criteria->compare('show_online',	$this->show_online);
		$criteria->compare('signature',		$this->signature,		true);
		$criteria->compare('timezone',		$this->timezone,		true);
		$criteria->compare('tumblr',		$this->tumblr,			true);
		$criteria->compare('twitter',		$this->twitter,			true);
		$criteria->compare('upvoted',		$this->upvoted);
		$criteria->compare('warning',		$this->warning);
		$criteria->compare('website',		$this->website,			true);
		$criteria->compare('wordpress',		$this->wordpress,		true);
		$criteria->compare('yahoo',			$this->yahoo,			true);
		$criteria->compare('youtube',		$this->youtube,			true);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}*/

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @param  [type] $params [description]
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($params){
		$query        = BbiiMember::find();
		$dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    if (!($this->load($params) && $this->validate())) {
	        return $dataProvider;
	    }

		$this->addCondition('avatar',		$this->avatar,			true);
		$this->addCondition('birthdate',	$this->birthdate,		true);
		$this->addCondition('blogger',		$this->blogger,			true);
		$this->addCondition('contact_email',$this->contact_email);
		$this->addCondition('contact_pm',	$this->contact_pm);
		$this->addCondition('facebook',		$this->facebook,		true);
		$this->addCondition('first_visit',	$this->first_visit,		true);
		$this->addCondition('flickr',		$this->flickr,			true);
		$this->addCondition('gender',		$this->gender);
		$this->addCondition('google',		$this->google,			true);
		$this->addCondition('group_id',		$this->group_id);
		$this->addCondition('id',			$this->id, 				true);
		$this->addCondition('last_visit',	$this->last_visit,		true);
		$this->addCondition('linkedin',		$this->linkedin,		true);
		$this->addCondition('location',		$this->location,		true);
		$this->addCondition('member_name',	$this->member_name,		true);
		$this->addCondition('metacafe',		$this->metacafe,		true);
		$this->addCondition('moderator',	$this->moderator,		true);
		$this->addCondition('myspace',		$this->myspace,			true);
		$this->addCondition('orkut',		$this->orkut,			true);
		$this->addCondition('personal_text',$this->personal_text,	true);
		$this->addCondition('posts',		$this->posts);
		$this->addCondition('show_online',	$this->show_online);
		$this->addCondition('signature',	$this->signature,		true);
		$this->addCondition('timezone',		$this->timezone,		true);
		$this->addCondition('tumblr',		$this->tumblr,			true);
		$this->addCondition('twitter',		$this->twitter,			true);
		$this->addCondition('upvoted',		$this->upvoted);
		$this->addCondition('warning',		$this->warning);
		$this->addCondition('website',		$this->website,			true);
		$this->addCondition('wordpress',	$this->wordpress,		true);
		$this->addCondition('yahoo',		$this->yahoo,			true);
		$this->addCondition('youtube',		$this->youtube,			true);

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
				'order' => 'last_visit DESC',
				'condition' => "last_visit > '$recent'",
			),
			'show' => array(
				'condition' => 'show_online = 1',
			),
			'hidden' => array(
				'condition' => 'show_online = 0',
			),
			'newest' => array(
				'order' => 'first_visit DESC',
				'limit' => 1,
			),
			'moderator' => array(
				'condition' => 'moderator = 1',
			),
		);
	}
}