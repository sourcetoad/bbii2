CREATE TABLE `bbii_log_topic` (
  `member_id` int(10) unsigned NOT NULL,
  `topic_id` int(10) unsigned NOT NULL,
  `forum_id` int(10) unsigned NOT NULL,
  `last_post_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`member_id`,`topic_id`),
  KEY `idx_log_forum_id` (`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;