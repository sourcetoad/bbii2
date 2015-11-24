BBii2
=====


##Copyright
Copyright (c) 2013-2015, Ronald van Belzen. All rights reserved. https://github.com/rbacui
Copyright (c) 2015, Sourcetoad, LLC. All rights reserved. http://www.sourcetoad.com
Copyright (c) 2015, David J Eddy, All rights reserved. https://github.com/davidjeddy

 - See accompanying LICENSE for license information.

BBii2 is an extension to the Yii2 Framework (http://www.yiiframework.com/) 
in the form of a module. BBii2 adds and integrates a lightweight webforum (a.k.a. 
bulletin board) to an existing Yii 2.0 application.


##Requirements

Yii 2 or above.
PHP 5.5 or above.
The application to which BBii2 is added needs to have a user table.


##Usage

* `composer required sourcetoad/bbii2`
* `yii migrate/up --migrationPath=./vendor/sourcetoad/bbii2/migrations`
* Optional: import sample data from bbii/data/sampledata.mysql.sql
* Create the subdirectory to storage avatars in; the application must have read/write privlages
* Edit your configuration to register the module in your applications configuration

~~~
[php]
'modules' => array(
	'forum' => array(
		'adminId'        => 1,
		'class'          => 'application\modules\bbii2\Module',
		'userClass'      => 'User',
		'userIdColumn'   => 'id',
		'userNameColumn' => 'username',
        'avatarStorage'  => '@webroot/storage/avatars/'
	),
),
~~~

* BBii2 requires a user database table to be present that at least contains an 
  integer User ID field and a varchar User name field. BBii2 also expects 
  Yii::$app->user->id to return the User ID, not the User name. The model for 
  the user table and the column names for the User ID column and the User name 
  column are part of the module options.
* Log in to your application with the user that has the User ID equal to the 
  value for the option 'adminId’ to acquire administration rights in BBii2.
* Navigate to http://<your base url>/forum. Click the link 'Forum settings’ and 
  set up the forum.

##Options

The following configuration options can be used:
adminId:        	the User ID (integer) for the user to receive the admin 
					authorization (default value: false). When the application
					uses rbac and the role 'admin’ exists the users that get 
					the role 'admin’ assigned will also be admin for BBii2.
avatarStorage:  	The directory in which uploaded avatar images are stored 
					relative to the application webroot directory (leading '/’ 
					required) (default value: '/avatar').
forumTitle:     	The name for the forum (default value: 'BBii2 Forum').
userClass:      	The model name of the database table that contains the user 
					authentication information for User ID and User name 
					(default value: 'User').
userIdColumn:   	The column name of the User class field that contains the 
					User ID (default value: 'id').
userNameColumn: 	The column name of the User class field that contains the 
					User name (default value: 'username').
userMailColumn: 	The column name of the User class field that contains the 
					User e-mail address (default value: false).
dbName:				The name of the db component to use to connect to the forum 
					database tables (default value: false)


##TODO

Re-add CKEditor - DJE : 2015-01-2015


##Versions

0.5.0 : 2015-11-24 migrated BBii2 to composer package management system