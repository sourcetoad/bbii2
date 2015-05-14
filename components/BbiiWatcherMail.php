<?php 
class BbiiWatcherMail {
	private $forum, $bbii_db, $user_db, $table, $id, $user, $email;
	
	/**
	 * Constructor
	 * @param string $forum the name of the sender/forum in the mail header
	 * @param string $component the database component to use for the db connection for bbii tables
	 * @param string $model the name for the database model for the user table
	 * @param string $table the name of the user database table that contains the user id, user name and e-mail address
	 * @param string $id the name of the user id field in the user database table
	 * @param string $user the name of the user name field in the user database table
	 * @param string $email the name of the e-mail address field in the user database table
	 */
	public function __construct($forum = 'Forum', $component = 'db', $model = 'User', $table = 'user', $id = 'id', $user = 'username', $email = 'email') {
		$this->forum 	= $forum;
		$this->bbii_db 	= Yii::$app->getComponent($component);
		$class = new $model;
		$this->user_db 	= $class::find()->getDbConnection();
		$this->table 	= $table;
		$this->id 		= $id;
		$this->user 	= $user;
		$this->email 	= $email;
	}
	
	public function processWatchers($url = null) {
		if($this->email) {
			if(empty($url)) {
				$url = Yii::$app->createAbsoluteUrl('/');
			}
			// forum settings:
			$select = 'select * from bbii_setting';
			$cmd = $this->bbii_db->createCommand($select);
			$row_setting = $cmd->queryRow();
			$from = $row_setting['contact_email'];
			list($dummy, $host) = explode('@', $from);
			$subject = '';
			
			// watchers:
			$bbiiTopics = new BbiiTopicsRead;
			$select = 'select * from bbii_topic_read';
			$cmd = $this->bbii_db->createCommand($select);
			$result = $cmd->query();
			foreach($result as $row) {
				$send = false;
				$content = '';
				$topics = array();
				// watcher:
				$select = "select * from {$this->table} where {$this->id} = {$row['user_id']}";
				$cmd_user = $this->user_db->createCommand($select);
				$row_user = $cmd_user->queryRow();
				if($row_user && isset($row_user[$this->email])) {
					// member:
					$select = "select * from bbii_member where id = {$row['user_id']}";
					$cmd_member = $this->bbii_db->createCommand($select);
					$row_member = $cmd_member->queryRow();
					// topics:
					$bbiiTopics->unserialize($row['data']);
					foreach($bbiiTopics->getFollow() as $topicId => $postId) {
						// posts:
						$select = "select p.id, p.topic_id, p.content, p.user_id, p.create_time, t.title from bbii_post p left join bbii_topic t on p.topic_id = t.id where topic_id = {$topicId} and p.id > {$postId} and p.approved = 1 order by t.id";
						$cmd01 = $this->bbii_db->createCommand($select);
						$res01 = $cmd01->query();
						foreach($res01 as $row_post) {
							$topics[$row_post['topic_id']] = $row_post['title'];
							$send = true;
							$bbiiTopics->setFollow($topicId, $row_post['id']);
							
							$content .= '<hr>' . PHP_EOL;
							$content .= "<h3>{$row_post['title']}</h3>" .PHP_EOL;
							
							// recalculate post create timestamp:
							$timestamp = DateTimeCalculation::convertTimestamp($row_post['create_time'], $row_member['timezone']);
							$df = Yii::$app->dateFormatter;
							$timestamp = $df->formatDateTime($timestamp, 'long', 'medium') . ' ';
							$dateTime = new DateTime(); 
							$dateTime->setTimeZone(new DateTimeZone($row_member['timezone'])); 
							$timestamp .= $dateTime->format('T');
							
							// retrieve poster name:
							$select = "select * from bbii_member where id = {$row_post['user_id']}";
							$cmd_poster = $this->bbii_db->createCommand($select);
							$row_poster = $cmd_poster->queryRow();
							
							$content .= "<h4>{$row_poster['member_name']} &raquo; {$timestamp}</h4>" .PHP_EOL;
							$content .= $row_post['content'];
							$content .= '<br>' . PHP_EOL;
						}
					}
					$update = "update bbii_topic_read set data = '" . $bbiiTopics->serialize() . "' where user_id = {$row['user_id']}";
					$cmd_read = $this->bbii_db->createCommand($update);
					$cmd_read->execute();
				}
				if($send) {
					$precontent = $row_member['member_name'] . ',<br><br>';
					$precontent .= Yii::t('BbiiModule.bbii', 'This is the digest of posts in the topic(s) "{topics}" for today.', array('{topics}'=>implode('", "', $topics)));
					$precontent .= '<br>';
					$postcontent =  '<hr><br>' . Yii::t('BbiiModule.bbii', 'You can unsubscribe at any time by logging into the {forum site} and visit your forum profile page.', array('{forum site}'=>CHtml::link(Yii::t('BbiiModule.bbii', 'website'), $url)));
					$content = '<html><body>' . $precontent . $content . $postcontent . '</body></html>';
					
					//$name='=?UTF-8?B?'.base64_encode($this->forum).'?=';
					$name = $this->forum;
					$subject='=?UTF-8?B?'.base64_encode(Yii::t('BbiiModule.bbii', 'Your daily new posts digest')).'?=';
					$sendto = "{$row_member['member_name']} <{$row_user[$this->email]}>";
					$headers="From: $name <$from>\r\n".
						"To: {$sendto}\r\n".
						"Date: " . date(DATE_RFC2822) . "\r\n".
						"Reply-To: $from\r\n".
						"Message-ID: <" . uniqid('', true) . "@{$host}>\r\n".
						"MIME-Version: 1.0\r\n".
						"Content-type: text/html; charset=UTF-8";

					mail($sendto,$subject,$content,$headers);
				}
			}
		}
	}
}