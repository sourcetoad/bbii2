CREATE TABLE `bbii2_forum` (
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

CREATE TABLE `bbii2_ipaddress` (
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

CREATE TABLE `bbii2_member` (
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

CREATE TABLE `bbii2_membergroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text,
  `min_posts` smallint(6) NOT NULL DEFAULT '-1',
  `color` varchar(6) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii2_message` (
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

CREATE TABLE `bbii2_post` (
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

CREATE TABLE `bbii2_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii2_topic` (
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

CREATE TABLE `bbii2_topic_read` (
  `user_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bbii2_session` (
  `id` varchar(128) NOT NULL,
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii2_upvoted` (
  `member_id` int(10) unsigned NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  KEY `idx_upvoted_member` (`member_id`),
  KEY `idx_upvoted_post` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii2_poll` (
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

CREATE TABLE `bbii2_choice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `choice` varchar(200) NOT NULL,
  `poll_id` int(10) unsigned NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_choice_poll` (`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii2_vote` (
  `poll_id` int(10) unsigned NOT NULL,
  `choice_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`poll_id`,`choice_id`,`user_id`),
  KEY `idx_vote_poll` (`poll_id`),
  KEY `idx_vote_user` (`user_id`),
  KEY `idx_vote_choice` (`choice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `bbii2_spider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `bbii2_membergroup` (`id`, `name`, `color`) VALUES (0,'Members','0000ff');
UPDATE `bbii2_membergroup` SET `id` = 0 WHERE `id` = 1;
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('AddThis','AddThis.com robot tech.support@clearspring.com');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('AhrefsBot','Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('archive.org_bot','Mozilla/5.0 (compatible; archive.org_bot; Wayback Machine Live Record; +http://archive.org/details/archive.org_bot)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Baiduspider','Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Bingbot','Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Blekkobot','Mozilla/5.0 (compatible; Blekkobot; ScoutJet; +http://blekko.com/about/blekkobot)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('BLEXBot','Mozilla/5.0 (compatible; BLEXBot/1.0; +http://webmeup-crawler.com/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('CareerBot','Mozilla/5.0 (compatible; CareerBot/1.1; +http://www.career-x.de/bot.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('ChangeDetection','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;  http://www.changedetection.com/bot.html )');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('CocCoc','Mozilla/5.0 (compatible; coccoc/1.0; +http://help.coccoc.com/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Daumoa','Mozilla/5.0 (compatible; MSIE or Firefox mutant; not on Windows server; + http://tab.search.daum.net/aboutWebSearch.html) Daumoa/3.0');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('DotBot','Mozilla/5.0 (compatible; DotBot/1.1; http://www.opensiteexplorer.org/dotbot, help@moz.com)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('EasouSpider','Mozilla/5.0 (compatible; EasouSpider; +http://www.easou.com/search/spider.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Exabot','Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Exabot','Mozilla/5.0 (compatible; Exabot/3.0 (BiggerBetter); +http://www.exabot.com/go/robot)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('FlipboardProxy','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:28.0) Gecko/20100101 Firefox/28.0 (FlipboardProxy/1.1; +http://flipboard.com/browserproxy)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Genieo','Mozilla/5.0 (compatible; Genieo/1.0 http://www.genieo.com/webfilter.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Googlebot','Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Googlebot-Mobile','Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('GrapeshotCrawler','Mozilla/5.0 (compatible; GrapeshotCrawler/2.0; +http://www.grapeshot.co.uk/crawler.php)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('HubSpot Crawler','HubSpot Crawler 1.0 http://www.hubspot.com/');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('ia_archiver','ia_archiver (+http://www.alexa.com/site/help/webmasters; crawler@alexa.com)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Ichiro','ichiro/3.0 (http://search.goo.ne.jp/option/use/sub4/sub4-1/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('LinkedInBot','LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Magpie','magpie-crawler/1.1 (U; Linux amd64; en-GB; +http://www.brandwatch.net)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Mail.RU_Bot','Mozilla/5.0 (compatible; Linux x86_64; Mail.RU_Bot/Img/2.0; +http://go.mail.ru/help/robots)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Meanpathbot','Mozilla/5.0 (compatible; meanpathbot/1.0; +http://www.meanpath.com/meanpathbot.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('MetaJobBot','Mozilla/5.0 (compatible; MetaJobBot; http://www.metajob.at/crawler)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('MJ12bot','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('MSNbot','msnbot/2.0b (+http://search.msn.com/msnbot.htm)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Netseer','Mozilla/5.0 (compatible; Netseer crawler/2.0; +http://www.netseer.com/crawler.html; crawler@netseer.com)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Omgilibot','omgilibot/0.4 +http://omgili.com');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Proximic','Mozilla/5.0 (compatible; proximic; +http://www.proximic.com/info/spider.php)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('R6_bot','R6_CommentReader(www.radian6.com/crawler)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('SearchmetricsBot','Mozilla/5.0 (compatible; SearchmetricsBot; http://www.searchmetrics.com/en/searchmetrics-bot/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('SEOENGWorldBot','SEOENGWorldBot/1.0 (+http://www.seoengine.com/seoengbot.htm)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('SEOkicks-Robot','Mozilla/5.0 (compatible; SEOkicks-Robot; +http://www.seokicks.de/robot.html)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('SeznamBot','Mozilla/5.0 (compatible; SeznamBot/3.2; +http://fulltext.sblog.cz/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('ShopWiki','ShopWiki/1.0 ( +http://www.shopwiki.com/wiki/Help:Bot)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('SISTRIX Crawler','Mozilla/5.0 (compatible; SISTRIX Crawler; http://crawler.sistrix.net/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Sogou web spider','Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('SPbot','Mozilla/5.0 (compatible; spbot/4.1.0; +http://OpenLinkProfiler.org/bot )');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Spinn3r','Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.19; aggregator:Spinn3r (Spinn3r 3.1); http://spinn3r.com/robot) Gecko/2010040121 Firefox/3.0.19');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Vagabondo','Mozilla/4.0 (compatible;  Vagabondo/4.0; webcrawler at wise-guys dot nl; http://webagent.wise-guys.nl/; http://www.wise-guys.nl/)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('VoilaBot','Mozilla/5.0 (Windows NT 5.1; U; Win64; fr; rv:1.8.1) VoilaBot BETA 1.2 (support.voilabot@orange-ftgroup.com)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('WeSEE','WeSEE');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Woko robot','Woko robot 3.0');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Yahoo!Slurp','Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('YandexBot','Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)');
INSERT INTO `bbii2_spider` (`name`,`user_agent`) VALUES ('Yeti','Yeti/1.1 (Naver Corp.; http://help.naver.com/robots/)');
