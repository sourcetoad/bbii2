<?php

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
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BbiiMember the static model class
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
			array('posts, group_id, upvoted', 'numerical', 'integerOnly'=>true),
			array('gender, show_online, contact_email, contact_pm, warning, moderator', 'numerical', 'integerOnly'=>true, 'max'=>1),
			array('member_name', 'unique', 'on'=>'update'),
			array('member_name', 'length', 'max'=>45),
			array('image', 'file', 'allowEmpty' => true,'maxSize' => 1025000, 'types' => 'gif, jpg, jpeg, png'),
			array('location, personal_text, avatar, blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube', 'length', 'max'=>255),
			array('timezone', 'length', 'max'=>80),
			array('gender, birthdate, location, personal_text, signature, avatar', 'default', 'value'=>null),
			array('blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube', 'url'),
			array('blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube', 'default', 'value'=>null),
			array('timezone', 'default', 'value'=>'Europe/London'),
			array('signature','filter','filter'=>array($obj=new CHtmlPurifier(), 'purify')),
			array('birthdate, signature, first_visit, last_visit, remove_avatar', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, member_name, gender, birthdate, location, personal_text, signature, avatar, show_online, contact_email, contact_pm, timezone, first_visit, last_visit, warning, posts, group_id, upvoted, blogger, facebook, flickr, google, linkedin, metacafe, myspace, orkut, tumblr, twitter, website, wordpress, yahoo, youtube, moderator', 'safe', 'on'=>'search'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'image' => Yii::t('BbiiModule.bbii', 'Upload a new avatar'),
			'remove_avatar' => Yii::t('BbiiModule.bbii', 'Remove your avatar'),
			'member_name' => Yii::t('BbiiModule.bbii', 'Name to display'),
			'gender' => Yii::t('BbiiModule.bbii', 'Gender'),
			'birthdate' => Yii::t('BbiiModule.bbii', 'Birthdate'),
			'location' => Yii::t('BbiiModule.bbii', 'Location'),
			'personal_text' => Yii::t('BbiiModule.bbii', 'Profile text'),
			'signature' => Yii::t('BbiiModule.bbii', 'Signature'),
			'avatar' => 'Avatar',
			'show_online' => Yii::t('BbiiModule.bbii', 'Show when online'),
			'contact_email' => Yii::t('BbiiModule.bbii', 'Allow members to contact you by e-mail'),
			'contact_pm' => Yii::t('BbiiModule.bbii', 'Allow members to contact you by private messaging'),
			'timezone' => Yii::t('BbiiModule.bbii', 'Time zone'),
			'first_visit' => 'First Visit',
			'last_visit' => 'Last Visit',
			'warning' => 'Warning',
			'posts' => 'Posts',
			'group_id' => Yii::t('BbiiModule.bbii', 'Group'),
			'upvoted' => 'Upvoted',
			'blogger' => Yii::t('BbiiModule.bbii', 'My Blogger blog'),
			'facebook' => Yii::t('BbiiModule.bbii', 'My Facebook page'),
			'flickr' => Yii::t('BbiiModule.bbii', 'My Flickr account'),
			'google' => Yii::t('BbiiModule.bbii', 'My Google+ page'),
			'linkedin' => Yii::t('BbiiModule.bbii', 'My Linkedin page'),
			'metacafe' => Yii::t('BbiiModule.bbii', 'My Metacafe channel'),
			'myspace' => Yii::t('BbiiModule.bbii', 'My Myspace page'),
			'orkut' => Yii::t('BbiiModule.bbii', 'My Orkut page'),
			'tumblr' => Yii::t('BbiiModule.bbii', 'My Tumblr blog'),
			'twitter' => Yii::t('BbiiModule.bbii', 'My Twitter page'),
			'website' => Yii::t('BbiiModule.bbii', 'My website'),
			'wordpress' => Yii::t('BbiiModule.bbii', 'My Wordpress blog'),
			'yahoo' => Yii::t('BbiiModule.bbii', 'Yahoo'),
			'youtube' => Yii::t('BbiiModule.bbii', 'My Youtube channel'),
			'moderator' => Yii::t('BbiiModule.bbii', 'Moderator'),
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
		$criteria->compare('member_name',$this->member_name,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('personal_text',$this->personal_text,true);
		$criteria->compare('signature',$this->signature,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('show_online',$this->show_online);
		$criteria->compare('contact_email',$this->contact_email);
		$criteria->compare('contact_pm',$this->contact_pm);
		$criteria->compare('timezone',$this->timezone,true);
		$criteria->compare('first_visit',$this->first_visit,true);
		$criteria->compare('last_visit',$this->last_visit,true);
		$criteria->compare('warning',$this->warning);
		$criteria->compare('posts',$this->posts);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('upvoted',$this->upvoted);
		$criteria->compare('blogger',$this->blogger,true);
		$criteria->compare('facebook',$this->facebook,true);
		$criteria->compare('flickr',$this->flickr,true);
		$criteria->compare('google',$this->google,true);
		$criteria->compare('linkedin',$this->linkedin,true);
		$criteria->compare('metacafe',$this->metacafe,true);
		$criteria->compare('myspace',$this->myspace,true);
		$criteria->compare('orkut',$this->orkut,true);
		$criteria->compare('tumblr',$this->tumblr,true);
		$criteria->compare('twitter',$this->twitter,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('wordpress',$this->wordpress,true);
		$criteria->compare('yahoo',$this->yahoo,true);
		$criteria->compare('youtube',$this->youtube,true);
		$criteria->compare('moderator',$this->moderator,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function scopes() {
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