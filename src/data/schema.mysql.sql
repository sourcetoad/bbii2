CREATE TABLE `bbii_forum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `public` tinyint(4) NOT NULL DEFAULT '1',
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `moderated` tinyint(4) NOT NULL DEFAULT '0',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `num_posts` int(10) unsigned NOT NULL DEFAULT '0',
  `num_topics` int(10) unsigned NOT NULL DEFAULT '0',
  `last_post_id` int(10) unsigned DEFAULT NULL,
  `poll` tinyint(4) NOT NULL DEFAULT '0',
  `membergroup_id` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bbii_ipaddress` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(39) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `source` tinyint(4) DEFAULT '0',
  `count` int(11) DEFAULT '0',
  `create_time` timestamp NULL DEFAULT NULL,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_member` (
  `id` int(10) unsigned NOT NULL,
  `member_name` varchar(45) DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `personal_text` varchar(255) DEFAULT NULL,
  `signature` text,
  `avatar` varchar(255) DEFAULT NULL,
  `show_online` tinyint(4) DEFAULT '1',
  `contact_email` tinyint(4) DEFAULT '0',
  `contact_pm` tinyint(4) DEFAULT '1',
  `timezone` varchar(80) DEFAULT NULL,
  `first_visit` timestamp NULL DEFAULT NULL,
  `last_visit` timestamp NULL DEFAULT NULL,
  `warning` tinyint(4) DEFAULT '0',
  `posts` int(10) unsigned DEFAULT '0',
  `group_id` tinyint(4) DEFAULT '0',
  `upvoted` smallint(6) DEFAULT '0',
  `blogger` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `flickr` varchar(255) DEFAULT NULL,
  `google` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `metacafe` varchar(255) DEFAULT NULL,
  `myspace` varchar(255) DEFAULT NULL,
  `orkut` varchar(255) DEFAULT NULL,
  `tumblr` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `wordpress` varchar(255) DEFAULT NULL,
  `yahoo` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `moderator` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_name_UNIQUE` (`member_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_membergroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `min_posts` smallint(6) NOT NULL DEFAULT '-1',
  `color` varchar(6) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sendfrom` int(10) unsigned NOT NULL,
  `sendto` int(10) unsigned NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `read_indicator` tinyint(4) NOT NULL DEFAULT '0',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `inbox` tinyint(4) NOT NULL DEFAULT '1',
  `outbox` tinyint(4) NOT NULL DEFAULT '1',
  `ip` varchar(39) NOT NULL,
  `post_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sendfrom_INDEX` (`sendfrom`),
  KEY `sendto_INDEX` (`sendto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `topic_id` int(10) unsigned DEFAULT NULL,
  `forum_id` int(10) unsigned DEFAULT NULL,
  `ip` varchar(39) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `change_id` int(10) unsigned DEFAULT NULL,
  `change_time` timestamp NULL DEFAULT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `upvoted` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_INDEX` (`user_id`),
  KEY `topic_id_INDEX` (`topic_id`),
  KEY `create_time_INDEX` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_topic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `first_post_id` int(10) unsigned NOT NULL,
  `last_post_id` int(10) unsigned NOT NULL,
  `num_replies` int(10) unsigned NOT NULL DEFAULT '0',
  `num_views` int(10) unsigned NOT NULL DEFAULT '0',
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `locked` tinyint(4) NOT NULL DEFAULT '0',
  `sticky` tinyint(4) NOT NULL DEFAULT '0',
  `global` tinyint(4) NOT NULL DEFAULT '0',
  `moved` int(10) unsigned NOT NULL DEFAULT '0',
  `upvoted` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `last_post_id` (`last_post_id`),
  KEY `forum_id` (`forum_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bbii_topic_read` (
  `user_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bbii_session` (
  `id` varchar(128) NOT NULL,
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_upvoted` (
  `member_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  KEY `idx_upvoted_member` (`member_id`),
  KEY `idx_upvoted_post` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_poll` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(200) NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `expire_date` date DEFAULT NULL,
  `allow_revote` tinyint(4) NOT NULL DEFAULT '0',
  `allow_multiple` tinyint(4) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_poll_post` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_choice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `choice` varchar(200) NOT NULL,
  `poll_id` int(10) unsigned NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_choice_poll` (`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_vote` (
  `poll_id` int(10) unsigned NOT NULL,
  `choice_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`poll_id`,`choice_id`,`user_id`),
  KEY `idx_vote_poll` (`poll_id`),
  KEY `idx_vote_user` (`user_id`),
  KEY `idx_vote_choice` (`choice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii_spider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;