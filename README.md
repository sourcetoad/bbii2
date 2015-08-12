BBii2
=====



##Copyright
See ./LICENSE



##Description
BBii2 is a module for the Yii2 Framework (http://www.yiiframework.com/).
BBii2 adds and integrates a lightweight webforum (a.k.a. bulletin board)
to an existing Yii 2.0 application.



##Options

The following configuration options can be used:
```php
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
topicsPerPage:		The number of topics to display on a single page (default value: 20).
postsPerPage:		The number of posts to display on a single page (default value: 20).
purifierOptions:	The CHtmlPurifier options.
```


##Requirements

Yii 2.0+ with some sort of user account system based on a database table
PHP 5.5+

* BBii2 requires a user database table to be present that at least contains an 
  integer User `id` field, a varchar `Username` field, and finally
  `\Yii::$app->user->identity->id` to return the User ID. The model for 
  the user table and the column names for the User ID column and the User name 
  column are part of the module options.
* Log in to your application with the user that has the User ID equal to the 
  value(s) for the option 'adminId’ to acquire administration rights in BBii2.
* Navigate to http://<your base url>/forum. Click the link 'Forum settings’ and 
  set up the forum.


##Usage

* Install:
 * `php composer.phar require sourcetoad/yii2-bbii2:2.*`
 * Create the subdirectory 'avatar’ in your application webroot directory to which 
  the application must be given read/write access rights.
 * Edit your configuration to register the module (the default option values may 
  need to be adjusted):
 * Now configure via the application config, typically ./<app root/frontend/config/web.php
```php
	'modules' => [
		...
		'bbii2' => [
			'adminId'        => [1],
			'class'          => 'common\models\User',
			'userClass'      => 'User',
			'userIdColumn'   => 'id',
			'userNameColumn' => 'username',
			'avatarStorage'  => '../assets/avatars/',
		],
		...
	],
```
* Update:
 * `php composer.phar update sourcetoad/yii2-bbii2`

* Sample Data:
 * Create database tables according to bbii/data/schema.mysql.sql
 * Import sample data from bbii/data/sampledata.mysql.sql



##Versions
* v2.0.5 (Aug 2015)
 * Converted integrated forum into a composer package module
* v2.0.0 (June 1, 2015)
 * Migrated `Bbii 1x` to Yii2 as `Bbii 2.x`
* v1.0.9 (March 18, 2015)
 * Added topic subscription.
 * CKEditor plugin support added to editMe extension.
* v1.0.8 (February 28, 2015):
 * Changed CListView template and pager for topics and posts.
 * Changed paging through topic posts by adding scroll to top.
 * Minor improvements to the base forum.css file.
 * Minor bugfix.
* v1.0.7 (January 22, 2015):
 * Russian language file added.
 * Minor bugfixes.
* v1.0.6 (January 15, 2015):
 * Reworked forum/topic read indication.
* v1.0.5 (September 1, 2014):
 * Added database connection component name in configuration.
* v1.0.4 (July 16, 2014):
 * Bugfix.
* v1.0.3 (July 15, 2014):
 * Bugfix.
* v1.0.2 (June 28, 2014):
 * Page titles reflecting forum names and topic titles. 
 * Indicate topics with own posts.
 * Excluded spiderbots/webcrawlers from forum statistics.
 * Sanitized displays for absense of Javascript.
* v1.0.1 (June  7, 2014): 
 * Collapse forum groups.
* v1.0.0 (May  25, 2014): 
 * Improved support for theming.
* v0.94  (May  17, 2014): 
 * Removed dependency on module id from the code. Bugfixes.
* v0.93  (May  12, 2014): 
 * ColorPicker removed. Upgraded editMe to version 2.1.
 * Added CKEditor 'skin' configuration to editMe 2.1. 
 * Upgraded CKEditor to version 4.4.0. 
 * Included CKEditor skins 'moonocolor' and 'kama'.
* v0.92 (May  10, 2014): 
 * Bugfixes.
* v0.91 (Apr. 25, 2014): 
 * Bugfixes.
* v0.9 (Apr.  6, 2014): 
 * Replaced forum and topic images by sprites.
 * Moderator mail. Membergroup forums.
* v0.82 (Mar. 26, 2014): 
 * Improvements to private messaging.
* v0.81 (Mar. 16, 2014): 
 * Security related bugfixes.
* v0.8 (Nov. 30, 2013): 
 * More module options. A grey-themed css file. 
 * German translation file.
* v0.7 (Nov. 16, 2013): 
 * Polls.
* v0.6 (Nov.  1, 2013): 
 * Post upvoting.
* v0.5  (Aug. 20, 2013): 
 * Indicate unread forums and topics.
* v0.4 (Aug. 18, 2013): 
 * Moderator assignment. Member group icons.
* v0.3 (Aug.  4, 2013): 
 * User e-mail form added. Session count for statistics.
* v0.2  (Aug.  1, 2013): 
 * Search functionality added.
* v0.1 (Jul. 27, 2013): 
 * Initial release.

##TESTs
* Where are your tests? -_-...about that...