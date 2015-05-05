<?php

/**
 * MailForm class.
 */
class MailForm extends CFormModel
{
	public $member_id;
	public $member_name;
	public $subject;
	public $body;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('member_id, subject, body', 'required'),
			array('body','filter','filter'=>array($obj=new CHtmlPurifier(), 'purify')),
			array('member_name', 'safe'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'subject'=>Yii::t('BbiiModule.bbii','Subject'),
		);
	}
}