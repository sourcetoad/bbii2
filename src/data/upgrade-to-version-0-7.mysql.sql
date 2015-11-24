ALTER TABLE `bbii_forum` ADD COLUMN `poll` TINYINT NOT NULL DEFAULT '0'  AFTER `last_post_id` ;

CREATE TABLE `bbii_poll` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(200) NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `expire_date` date DEFAULT NULL,
  `allow_revote` tinyint(4) NOT NULL DEFAULT '0',
  `allow_multiple` tinyint(6) NOT NULL DEFAULT '0',
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