DROP TABLE `bbii_log_topic`;

CREATE TABLE `bbii_topic_read` (
  `user_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;