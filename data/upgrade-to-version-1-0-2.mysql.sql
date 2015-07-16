CREATE TABLE `bbii_spider` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('AddThis','AddThis.com robot tech.support@clearspring.com');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('AhrefsBot','Mozilla/5.0 (compatible; AhrefsBot/5.0; +http://ahrefs.com/robot/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('archive.org_bot','Mozilla/5.0 (compatible; archive.org_bot; Wayback Machine Live Record; +http://archive.org/details/archive.org_bot)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Baiduspider','Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Bingbot','Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Blekkobot','Mozilla/5.0 (compatible; Blekkobot; ScoutJet; +http://blekko.com/about/blekkobot)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('BLEXBot','Mozilla/5.0 (compatible; BLEXBot/1.0; +http://webmeup-crawler.com/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('CareerBot','Mozilla/5.0 (compatible; CareerBot/1.1; +http://www.career-x.de/bot.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('ChangeDetection','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;  http://www.changedetection.com/bot.html )');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('CocCoc','Mozilla/5.0 (compatible; coccoc/1.0; +http://help.coccoc.com/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Daumoa','Mozilla/5.0 (compatible; MSIE or Firefox mutant; not on Windows server; + http://tab.search.daum.net/aboutWebSearch.html) Daumoa/3.0');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('DotBot','Mozilla/5.0 (compatible; DotBot/1.1; http://www.opensiteexplorer.org/dotbot, help@moz.com)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('EasouSpider','Mozilla/5.0 (compatible; EasouSpider; +http://www.easou.com/search/spider.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Exabot','Mozilla/5.0 (compatible; Exabot/3.0; +http://www.exabot.com/go/robot)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Exabot','Mozilla/5.0 (compatible; Exabot/3.0 (BiggerBetter); +http://www.exabot.com/go/robot)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('FlipboardProxy','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:28.0) Gecko/20100101 Firefox/28.0 (FlipboardProxy/1.1; +http://flipboard.com/browserproxy)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Genieo','Mozilla/5.0 (compatible; Genieo/1.0 http://www.genieo.com/webfilter.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Googlebot','Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Googlebot-Mobile','Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('GrapeshotCrawler','Mozilla/5.0 (compatible; GrapeshotCrawler/2.0; +http://www.grapeshot.co.uk/crawler.php)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('HubSpot Crawler','HubSpot Crawler 1.0 http://www.hubspot.com/');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('ia_archiver','ia_archiver (+http://www.alexa.com/site/help/webmasters; crawler@alexa.com)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Ichiro','ichiro/3.0 (http://search.goo.ne.jp/option/use/sub4/sub4-1/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('LinkedInBot','LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta Commons-HttpClient/3.1 +http://www.linkedin.com)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Magpie','magpie-crawler/1.1 (U; Linux amd64; en-GB; +http://www.brandwatch.net)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Mail.RU_Bot','Mozilla/5.0 (compatible; Linux x86_64; Mail.RU_Bot/Img/2.0; +http://go.mail.ru/help/robots)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Meanpathbot','Mozilla/5.0 (compatible; meanpathbot/1.0; +http://www.meanpath.com/meanpathbot.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('MetaJobBot','Mozilla/5.0 (compatible; MetaJobBot; http://www.metajob.at/crawler)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('MJ12bot','Mozilla/5.0 (compatible; MJ12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('MSNbot','msnbot/2.0b (+http://search.msn.com/msnbot.htm)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Netseer','Mozilla/5.0 (compatible; Netseer crawler/2.0; +http://www.netseer.com/crawler.html; crawler@netseer.com)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Omgilibot','omgilibot/0.4 +http://omgili.com');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Proximic','Mozilla/5.0 (compatible; proximic; +http://www.proximic.com/info/spider.php)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('R6_bot','R6_CommentReader(www.radian6.com/crawler)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('SearchmetricsBot','Mozilla/5.0 (compatible; SearchmetricsBot; http://www.searchmetrics.com/en/searchmetrics-bot/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('SEOENGWorldBot','SEOENGWorldBot/1.0 (+http://www.seoengine.com/seoengbot.htm)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('SEOkicks-Robot','Mozilla/5.0 (compatible; SEOkicks-Robot; +http://www.seokicks.de/robot.html)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('SeznamBot','Mozilla/5.0 (compatible; SeznamBot/3.2; +http://fulltext.sblog.cz/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('ShopWiki','ShopWiki/1.0 ( +http://www.shopwiki.com/wiki/Help:Bot)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('SISTRIX Crawler','Mozilla/5.0 (compatible; SISTRIX Crawler; http://crawler.sistrix.net/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Sogou web spider','Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('SPbot','Mozilla/5.0 (compatible; spbot/4.1.0; +http://OpenLinkProfiler.org/bot )');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Spinn3r','Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.19; aggregator:Spinn3r (Spinn3r 3.1); http://spinn3r.com/robot) Gecko/2010040121 Firefox/3.0.19');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Vagabondo','Mozilla/4.0 (compatible;  Vagabondo/4.0; webcrawler at wise-guys dot nl; http://webagent.wise-guys.nl/; http://www.wise-guys.nl/)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('VoilaBot','Mozilla/5.0 (Windows NT 5.1; U; Win64; fr; rv:1.8.1) VoilaBot BETA 1.2 (support.voilabot@orange-ftgroup.com)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('WeSEE','WeSEE');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Woko robot','Woko robot 3.0');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Yahoo!Slurp','Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('YandexBot','Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)');
INSERT INTO `bbii_spider` (`name`,`user_agent`) VALUES ('Yeti','Yeti/1.1 (Naver Corp.; http://help.naver.com/robots/)');
