-- Host: localhost    Database: phparcade

SET UNIQUE_CHECKS=1;
SET FOREIGN_KEY_CHECKS=1;
SET SQL_MODE='NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES';

CREATE SCHEMA IF NOT EXISTS `phparcade`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE `phparcade`;

--
-- Table structure for table `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `code` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `advertisername` varchar(255) NOT NULL DEFAULT '',
  `adcomment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ads` (`name`,`location`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT 'Games',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `order` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_cat_lookup` (`name`,`type`,`desc`,`keywords`,`order`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (1,'Adventure','Games','Adventure Games are games that are more like RPG role playing games or consist of travelling amongst screens','adventure',2);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (2,'Arcade','Games','Arcade Games are games that you used to play in the arcade when you were younger or for Sega Genesis, Nintendo, Nintendo 64, Playstation and more','arcade,console,wii,xbox',3);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (3,'Casino','Games','Games that you would find at the Casino in Las Vegas or Atlantic City as well as card games that you might play around the house.','gamble, casino, card, cards, bet, childrens games',4);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (4,'Driving','Games','These are games where you drive around the town completing objectives or where you just need to race another person or the clock on a motorcycle or sportscar','drive, race, car, speed, fast, park, tires, wheels, driving games, racing games',5);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (5,'Flying','Games','Flying and airplane games','fly,airplane',6);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (6,'Shooting','Games','Games where you can shoot your pistol, shotgun, assault rifle, sniper rifle or other high powered gun','FPS,shooting,first person shooter',8);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (7,'Simulation','Games','Simulation Games','simulation',9);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (8,'Sports','Games','Sports Games are games like football, baseball, soccer, cricket, lacross and other sports and ball games','sports',10);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (9,'Strategy','Games','Games that require having a strategy at hand','strategy games',11);
INSERT INTO `phparcade`.`categories` (`id`,`name`,`type`,`desc`,`keywords`,`order`) VALUES (10,'Word','Games','Word and Number puzzle games and other games like hangman and hangaroo that make you play with words and letters and numbers to win','word,number',12);

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `key` varchar(255) NOT NULL DEFAULT 'primarykey',
  `value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`key`),
  UNIQUE KEY `name_UNIQUE` (`key`),
  KEY `idx_config_lookup` (`key`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `phparcade`.`config` SET `key`='defgheight',`value`='600';
INSERT INTO `phparcade`.`config` SET `key`='defgwidth',`value`='800';
INSERT INTO `phparcade`.`config` SET `key`='disqus_on',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='disqus_user',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='emailactivation',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='emaildebug',`value`='0';
INSERT INTO `phparcade`.`config` SET `key`='emaildomain',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='emailfrom',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='emailhost',`value`='smtp.gmail.com';
INSERT INTO `phparcade`.`config` SET `key`='emailport',`value`='587';
INSERT INTO `phparcade`.`config` SET `key`='facebook_appid',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='facebook_on',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='facebook_pageurl',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='gamesperpage',`value`='18';
INSERT INTO `phparcade`.`config` SET `key`='gtm_id', `value` ='';
INSERT INTO `phparcade`.`config` SET `key`='google_analytics_pubid', `value`='';
INSERT INTO `phparcade`.`config` SET `key`='google_recaptcha_secretkey', `value`='';
INSERT INTO `phparcade`.`config` SET `key`='google_recaptcha_sitekey', `value`='';
INSERT INTO `phparcade`.`config` SET `key`='google_search_ID',`value`='8727545858461215:8837170480';
INSERT INTO `phparcade`.`config` SET `key`='imgurl',`value`='http://localhost/img/';
INSERT INTO `phparcade`.`config` SET `key`='membersenabled',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='metadesc',`value`='PHPArcade is a free, open source (FOSS), online flash game arcade script. Download the GitHub script now to set up your own HTML5 and Flash game website for free!';
INSERT INTO `phparcade`.`config` SET `key`='metakey',`value`='Free,online,game,arcade,action,adventure,arcade,casino,card,driving,flying,shooting, simulation,sports,puzzle,strategy,racing,word';
INSERT INTO `phparcade`.`config` SET `key`='mixpanel_id',`value`='';
-- Order isn't used in the front-end, but is still needed for other functionality.
INSERT INTO `phparcade`.`config` SET `key`='order',`value`='name';
INSERT INTO `phparcade`.`config` SET `key`='passwordrecovery',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='rssenabled',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='rssfeed',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='rssnumlatest',`value`='15';
INSERT INTO `phparcade`.`config` SET `key`='sitetitle',`value`='phpArcade, Free Online Script';
INSERT INTO `phparcade`.`config` SET `key`='sort',`value`='ASC';
INSERT INTO `phparcade`.`config` SET `key`='theight',`value`='200';
INSERT INTO `phparcade`.`config` SET `key`='theme',`value`='bootstrap4';
INSERT INTO `phparcade`.`config` SET `key`='twidth',`value`='200';

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nameid` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `instructions` text NOT NULL,
  `cat` varchar(255) NOT NULL,
  `order` int(10) NOT NULL DEFAULT 0,
  `broken` enum('Yes','No') NOT NULL DEFAULT 'No',
  `brokenby` varchar(16) DEFAULT '',
  `width` int(10) NOT NULL DEFAULT 0,
  `height` int(10) NOT NULL DEFAULT 0,
  `rating` int(25) NOT NULL DEFAULT 0,
  `ratingcount` int(25) NOT NULL DEFAULT 0,
  `totalrating` int(25) NOT NULL DEFAULT 0,
  `type` enum('PNG','FLV','SWF','extlink','CustomCode') NOT NULL DEFAULT 'SWF',
  `playcount` int(10) NOT NULL DEFAULT 0,
  `weeklyplays` int(10) NOT NULL DEFAULT 0,
  `flags` varchar(15) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '-',
  `active` varchar(10) NOT NULL DEFAULT 'Yes',
  `time` int(10) NOT NULL DEFAULT 0,
  `release_date` int(10) DEFAULT 0,
  `authorsite` text DEFAULT NULL,
  `authorname` text DEFAULT NULL,
  `sponsorsite` text DEFAULT NULL,
  `sponsorname` text DEFAULT NULL,
  `customcode` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nameid_UNIQUE` (`nameid`),
  KEY `idx_cat_lookup` (`playcount`,`release_date`,`cat`,`nameid`,`name`,`width`,`height`,`type`,`flags`,`time`) USING BTREE,
  KEY `idx_game_lookup` (`id`,`nameid`,`name`,`desc`(100),`instructions`(100)) USING BTREE,
  KEY `idx_game_search` (`name`,`release_date`,`id`,`nameid`),
  KEY `idx_releasedate_lookup` (`id`,`active`,`release_date`,`cat`),
  KEY `idx_game_active` (`active`),
  KEY `idx_game_broken` (`broken`),
  FULLTEXT KEY `search` (`name`,`desc`,`instructions`,`keywords`)
) ENGINE=InnoDB AUTO_INCREMENT=2741 DEFAULT CHARSET=utf8;

--
-- Table structure for table `games_champs`
--

CREATE TABLE IF NOT EXISTS `games_champs` (
  `nameid` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `score` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`nameid`),
  KEY `idx_playerscore_lookup` (`nameid`,`player`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `games_score`
--

CREATE TABLE IF NOT EXISTS `games_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nameid` int(11) NOT NULL,
  `player` int(11) NOT NULL,
  `score` float NOT NULL DEFAULT 0,
  `ip` text NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `score` (`nameid`,`player`,`score`,`date`),
  UNIQUE KEY `dupe_check` (`nameid`,`player`,`score`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `active` varchar(10) NOT NULL DEFAULT 'Yes',
  `regtime` bigint(10) NOT NULL DEFAULT 0,
  `totalgames` int(10) NOT NULL DEFAULT 0,
  `facebook_id` varchar(255) DEFAULT NULL,
  `github_id` varchar(255) DEFAULT NULL,
  `twitter_id` varchar(255) NOT NULL DEFAULT '',
  `admin` varchar(10) NOT NULL DEFAULT 'No',
  `favorites` varchar(1) NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL,
  `birth_date` varchar(10) NOT NULL DEFAULT '{null}',
  `last_login` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`username`,`email`),
  KEY `members_active-totalgames` (`id`,`active`,`totalgames`),
  KEY `idx_members` (`id`,`username`,`totalgames`,`ip`,`last_login`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- Password is 'admin'
INSERT INTO `phparcade`.`members` SET
  `id` = 1,
  `username` = 'admin',
  `password` = '21232f297a57a5a743894a0e4a801fc3',
  `email` = 'admin@example.com',
  `active` = 'Yes',
  `regtime` = UNIX_TIMESTAMP(NOW()),
  `totalgames` = 0,
  `twitter_id` = '',
  `github_id` = NULL,
  `facebook_id` = NULL,
  `admin` = 'Yes',
  `favorites` = '',
  `ip` = '',
  `birth_date` = '',
  `last_login` = UNIX_TIMESTAMP(NOW());

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `showinmenu` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `phparcade`.`pages` (id, showinmenu, title, content, keywords, description) VALUES (1, 'No', 'Terms and Conditions', 'Insert Terms Here','','');
INSERT INTO `phparcade`.`pages` (id, showinmenu, title, content, keywords, description) VALUES (2, 'No', 'Privacy Policy','','','');
INSERT INTO `phparcade`.`pages` (id, showinmenu, title, content, keywords, description) VALUES (3, 'No', 'Partner Sites','','','');
INSERT INTO `phparcade`.`pages` (id, showinmenu, title, content, keywords, description) VALUES (4, 'No', 'About Us','','','');
INSERT INTO `phparcade`.`pages` (id, showinmenu, title, content, keywords, description) VALUES (5, 'No', 'Contact Us','','','');

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userid` int(10) NOT NULL DEFAULT 0,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sessions` (`id`,`userid`,`ip`,`time`),
  KEY `sessions_userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- Dumping routines for database 'phparcade'
--

-- ADS
DROP PROCEDURE IF EXISTS `sp_Ads_Delete_ID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Ads_Delete_ID`(
  IN adid INT(10))
  BEGIN
    DELETE FROM `ads`
    WHERE `id` = adid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Ads_GetAll_Random`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Ads_GetAll_Random`()
  BEGIN
    SELECT `id`,`code`
    FROM `ads`
    WHERE `id` >= rand() * (
      SELECT MAX(`id`)
      FROM `ads`
    ) - 1
    ORDER BY `id`
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Ads_GetAdbyID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Ads_GetAdbyID`(
  IN adid INT(10))
  BEGIN
    SELECT *
    FROM `ads`
    WHERE `id` = adid
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Ads_Insert`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Ads_Insert`(
  IN adid INT(10),
  IN adname VARCHAR(255),
  IN adcode TEXT,
  IN adlocation VARCHAR(255),
  IN advertiser VARCHAR(255),
  IN adcomments TEXT)
  BEGIN
    INSERT INTO `ads`
    (`id`, `name`, `code`, `location`, `advertisername`, `adcomment`)
    VALUES
      (adid, adname, adcode, adlocation, advertiser, adcomments);
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Ads_Update`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Ads_Update`(
  IN adid INT(10),
  IN adname VARCHAR(255),
  IN adcode TEXT,
  IN adlocation VARCHAR(255),
  IN advertiser VARCHAR(255),
  IN adcomments TEXT)
  BEGIN
    UPDATE `ads`
    SET `name` = adname,
      `code` = adcode,
      `location` = adlocation,
      `advertisername` = advertiser,
      `adcomment` = adcomments
    WHERE `id` = adid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Ads_GetAllbyName`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Ads_GetAllbyName`()
  BEGIN
    SELECT `id`, `name`,`location`
    FROM `ads`
    WHERE `location` != ''
    ORDER BY `name` ASC;
  END ;;
DELIMITER ;

-- Categories
DROP PROCEDURE IF EXISTS `sp_Categories_DeleteCategorybyID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_DeleteCategorybyID`(
  IN c_id INT(10))
  BEGIN
    DELETE FROM `categories`
    WHERE `id` = c_id;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_GetCategoryByID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_GetCategoryByID`(
  IN c_categoryid INT(10))
  BEGIN
    SELECT *
    FROM `categories`
    WHERE `id` = c_categoryid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_GetCategoryByName`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_GetCategoryByName`(
  IN c_name VARCHAR(255))
  BEGIN
    SELECT *
    FROM `categories`
    WHERE `name` = c_name;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_GetCategoriesByOrder_ASC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_GetCategoriesByOrder_ASC`()
  BEGIN
    SELECT *
    FROM `categories`
    ORDER BY `order` ASC;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_GetCategoriesByOrder_DESC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_GetCategoriesByOrder_DESC`()
  BEGIN
    SELECT *
    FROM `categories`
    ORDER BY `order` DESC;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_GetCategoryMaxID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_GetCategoryMaxID`()
  BEGIN
    SELECT MAX(`order`)
      AS maxOrder
    FROM `categories`;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_InsertCategory`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_InsertCategory`(
  IN c_catid INT(10),
  IN c_catname VARCHAR(255),
  IN c_catdesc VARCHAR(255),
  IN c_catkeywords VARCHAR(255),
  IN c_catorder INT(10),
  IN c_cattype VARCHAR(255))
  BEGIN
    INSERT INTO `categories`
    (`id`, `name`, `desc`, `keywords`, `order`, `type`)
    VALUES
      (c_catid, c_catname, c_catdesc, c_catkeywords, c_catorder, c_cattype);
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_UpdateCategory`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_UpdateCategory`(
  IN c_catid INT(10),
  IN c_catname VARCHAR(255),
  IN c_catdesc VARCHAR(255),
  IN c_catkeywords VARCHAR(255),
  IN c_cattype VARCHAR(255))
  BEGIN
    UPDATE `categories`
    SET
      `name` = c_catname,
      `type` = c_cattype,
      `desc` = c_catdesc,
      `keywords` = c_catkeywords
    WHERE `id` = c_catid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Categories_UpdateOrder`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_UpdateOrder`(
  IN c_order INT(10),
  IN c_id INT(10))
  BEGIN
    UPDATE `categories`
    SET `order` = c_order
    WHERE `id` = c_id;
  END ;;
DELIMITER ;

-- Config
DROP PROCEDURE IF EXISTS `sp_Config_Get`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Config_Get`()
  BEGIN
    SELECT *
    FROM `config`;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Config_Update`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Config_Update`(
  IN `dbkey` VARCHAR(255),
  IN `dbvalue` VARCHAR(255))
  BEGIN
    UPDATE `config`
    SET `value` = dbvalue
    WHERE `key` = dbkey;
  END ;;
DELIMITER ;

-- Games
DROP PROCEDURE IF EXISTS `sp_Games_AddGames`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_AddGames`(
  IN g_id INT(10),
  IN g_nameid VARCHAR(255),
  IN g_name VARCHAR(255),
  IN g_desc TEXT,
  IN g_instructions TEXT,
  IN g_category VARCHAR(255),
  IN g_order INT(10),
  IN g_width INT(10),
  IN g_height INT(10),
  IN g_type ENUM('PNG','FLV','SWF','extlink','CustomCode'),
  IN g_playcount INT(10),
  IN g_flags VARCHAR(15),
  IN g_keywords VARCHAR(255),
  IN g_time INT(10),
  IN g_release_date INT(10),
  IN g_customcode LONGTEXT)
  BEGIN
    INSERT INTO `games` (
      `id`, `nameid`, `name`, `desc`, `instructions`, `cat`, `order`, `width`, `height`, `type`, `playcount`, `flags`, `keywords`, `time`, `release_date`, `customcode`
    ) VALUES (
      g_id, g_nameid, g_name, g_desc, g_instructions, g_category, g_order, g_width, g_height, g_type, g_playcount, g_flags, g_keywords, g_time, g_release_date, g_customcode
    );
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_DeleteGamebyID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_DeleteGamebyID`(
  IN g_id INT(10))
  BEGIN
    DELETE FROM `games`
    WHERE `id` = g_id;
  END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `sp_Games_GetBrokenByID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetBrokenByID`()
  BEGIN
    SELECT `id`
      AS 'Count'
    FROM `games`
    WHERE `broken` = 'Yes';
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGamesByCategory_ASC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGamesByCategory_ASC`(
  IN g_category VARCHAR(255),
  IN g_release_date INT(10),
  IN g_limitstart INT(10),
  IN g_limitend INT(10))
  BEGIN
    SELECT *
    FROM `games`
    WHERE `cat` = g_category
          AND `active` = 'Yes'
          AND release_date <= g_release_date
    ORDER BY `name` ASC
    LIMIT g_limitstart, g_limitend;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGameByID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGameByID`(
  IN gameid INT(10))
  BEGIN
    SELECT *
    FROM `games`
    WHERE `id` = gameid
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGamebyNameid`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGamebyNameid`(
  IN g_nameid VARCHAR(255))
  BEGIN
    SELECT *
    FROM `games`
    WHERE `nameid` = g_nameid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGames_Active`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGames_Active`(
  IN g_active VARCHAR(10))
  BEGIN
    SELECT *
    FROM `games`
    FORCE INDEX(idx_game_active)
    WHERE `active`= g_active;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGames_ActivebyReleaseDate`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGames_ActivebyReleaseDate`(
  IN g_releasedate INT(10))
  BEGIN
    SELECT `id`
    FROM `games`
    WHERE `active` = 'Yes'
          AND release_date <= g_releasedate;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGames_ActivebyCategory`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGames_ActivebyCategory`(
  IN g_releasedate INT(10),
  IN g_category VARCHAR(255))
  BEGIN
    SELECT `id`
    FROM `games`
    WHERE `active` = 'Yes'
          AND release_date <= g_releasedate
          AND cat = g_category;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGamesByReleasedate_ASC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGamesByReleasedate_ASC`(
  IN g_release_date INT(10),
  IN g_limitstart INT(10),
  IN g_limitend INT(10))
  BEGIN
    SELECT *
    FROM `games`
    WHERE `release_date` != g_release_date
    ORDER BY `name` ASC
    LIMIT g_limitstart, g_limitend;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetGamesByReleasedate_DESC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGamesByReleasedate_DESC`(
  IN g_release_date INT(10),
  IN g_limitstart INT(10),
  IN g_limitend INT(10))
  BEGIN
    SELECT *
    FROM `games`
    WHERE `release_date` <= g_release_date
    ORDER BY `playcount` DESC
    LIMIT g_limitstart, g_limitend;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetIDandName`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetIDandName`()
  BEGIN
    SELECT `id`,`name`
    FROM `games`;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetNameidByCategory`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetNameidByCategory`(
  IN g_catname VARCHAR(255))
  BEGIN
    SELECT `nameid`
    FROM `games`
    WHERE `cat` = g_catname;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetPlaycount_Total`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetPlaycount_Total`()
  BEGIN
    SELECT SUM(`playcount`)
      AS 'playcount'
    FROM `games`;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetRandom4`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetRandom4`()
  BEGIN
    SELECT `id`,`nameid`,`name`,`desc`,`instructions`
    FROM `games`
    WHERE `id` >= rand() * (SELECT MAX(`id`) FROM `games` LIMIT 4) - 1
    ORDER BY `id`
    LIMIT 4;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_GetRandom8`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetRandom8`()
  BEGIN
    SELECT `id`,`nameid`,`name`,`desc`,`instructions`
    FROM `games`
    WHERE `id` >= rand() * (SELECT MAX(`id`) FROM `games` LIMIT 8) - 1
    ORDER BY `id`
    LIMIT 8;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_SearchbyText`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_SearchbyText`(
  IN g_releasedate INT(12),
  IN g_searchterm VARCHAR(255),
  IN g_limit INT(10))
  BEGIN
    SELECT `id`,`nameid`,`name`,`cat`,`desc`
    FROM `games`
    WHERE `active` = 'Yes'
          AND `release_date` <= g_releasedate
          AND MATCH (`name`,`desc`,`instructions`,`keywords`)
          AGAINST (g_searchterm WITH QUERY EXPANSION) LIMIT g_limit;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_UpdateGame`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_UpdateGame`(
  IN g_gamename VARCHAR(255),
  IN g_gamenameid VARCHAR(255),
  IN g_gamedesc TEXT,
  IN g_gamecat VARCHAR(255),
  IN g_gamekeywords VARCHAR(255),
  IN g_gameflags VARCHAR(15),
  IN g_gameinstructions TEXT,
  IN g_gamecustomcode LONGTEXT,
  IN g_gamewidth INT(10),
  IN g_gameheight INT(10),
  IN g_gameactive ENUM('Yes','No'),
  IN g_gamerelease INT(10),
  IN g_gameid INT(10))
  BEGIN
    UPDATE `games`
    SET
      `name`      = g_gamename,
      `nameid`    = g_gamenameid,
      `desc`      = g_gamedesc,
      `cat`       = g_gamecat,
      `keywords`  = g_gamekeywords,
      `flags`     = g_gameflags,
      `instructions` = g_gameinstructions,
      `customcode`= g_gamecustomcode,
      `width`     = g_gamewidth,
      `height`    = g_gameheight,
      `active`    = g_gameactive,
      `release_date` = g_gamerelease
    WHERE
      `id` = g_gameid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_UpdateGameOrder`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_UpdateGameOrder`(
  IN g_order INT(10),
  IN g_id INT(10))
  BEGIN
    UPDATE `games`
    SET `order` = g_order
    WHERE `id` = g_id;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Games_UpdateGamePlaycountbyID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_UpdateGamePlaycountbyID`(
  IN gameid INT(10))
  BEGIN
    UPDATE `games`
    SET `playcount` = `playcount` + 1
    WHERE `id` = gameid;
  END ;;
DELIMITER ;

-- Games_Champs
DROP PROCEDURE IF EXISTS `sp_GamesChamps_DeleteChampsbyGameID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesChamps_DeleteChampsbyGameID`(
  IN gc_gameid INT(11))
  BEGIN
    DELETE FROM `games_champs`
    WHERE
      `nameid` =  gc_gameid
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesChamps_GetChampsbyGame`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesChamps_GetChampsbyGame`(
  IN gc_nameid INT(11))
  BEGIN
    SELECT *
    FROM `games_champs`
    WHERE `nameid` = gc_nameid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesChamps_GetPlayerNameID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesChamps_GetPlayerNameID`(
  IN ch_playerid INT(10))
  BEGIN
    SELECT `nameid`,`player`
    FROM `games_champs`
    WHERE `player` = ch_playerid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GameChamps_UpdateChamp`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GameChamps_UpdateChamp`(
  IN gc_top_nameid INT(11),
  IN gc_top_player INT(11),
  IN gc_top_score FLOAT,
  IN gc_curr_time INT(10))
  BEGIN
    INSERT INTO `games_champs`
    SET
      `nameid`= gc_top_nameid,
      `player`= gc_top_player,
      `score` = gc_top_score,
      `date`  = gc_curr_time
    ON DUPLICATE KEY UPDATE
      `nameid`= gc_top_nameid,
      `player`= gc_top_player,
      `score` = gc_top_score,
      `date`  = gc_curr_time;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesChamps_UpdateScoresbyGame`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesChamps_UpdateScoresbyGame`(
  IN gc_date INT(10),
  IN gc_nameid INT(11),
  IN gc_score FLOAT,
  IN gc_player INT(11))
  BEGIN
    UPDATE `games_champs`
    SET `score` = gc_score,
      `date` = gc_date,
      `player` = gc_player
    WHERE `nameid` = gc_nameid
    LIMIT 1;
  END ;;
DELIMITER ;

-- Games_Score
DROP PROCEDURE IF EXISTS `sp_GamesScores_DeleteScoresbyGameID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesScores_DeleteScoresbyGameID`(
  IN gc_gameid INT(11))
  BEGIN
    DELETE FROM `games_score`
    WHERE
      `nameid` =  gc_gameid;
  END ;;
DELIMITER ;


DROP PROCEDURE IF EXISTS `sp_GamesScore_GetScores_ASC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesScore_GetScores_ASC`(
  IN gamenameid INT(11),
  IN limitnum INT(5))
  BEGIN
    SELECT *
    FROM `games_score`
    WHERE `nameid` = gamenameid
    ORDER BY `score` ASC
    LIMIT limitnum;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesScore_GetScores_DESC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesScore_GetScores_DESC`(
  IN gamenameid INT(11),
  IN limitnum INT(5))
  BEGIN
    SELECT *
    FROM `games_score`
    WHERE `nameid` = gamenameid
    ORDER BY `score` DESC
    LIMIT limitnum;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesScore_InsertNewGamesScore`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesScore_InsertNewGamesScore`(
  IN gs_ip TEXT,
  IN gs_date INT(10),
  IN gs_gamenameid INT(11),
  IN gs_gamescore FLOAT,
  IN gs_player INT(11))
  BEGIN
    INSERT INTO `games_score`
    (`nameid`, `player`, `score`, `ip`, `date`)
    VALUES
      (gs_gamenameid, gs_player, gs_gamescore, gs_ip, gs_date);
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesScore_ScoresRowCount`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesScore_ScoresRowCount`(
  IN gs_gamenameid INT(11),
  IN gs_player INT(11))
  BEGIN
    SELECT *
    FROM `games_score`
    WHERE `nameid` = gs_gamenameid
          AND `player` = gs_player;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_GamesScore_UpdateGamesScore`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_GamesScore_UpdateGamesScore`(
  IN gs_ip TEXT,
  IN gs_date INT(10),
  IN gs_gameid INT(11),
  IN gs_gamescore FLOAT,
  IN gs_player INT(11))
  BEGIN
    UPDATE `games_score`
    SET	`score` = gs_gamescore,
      `ip` = gs_ip,
      `date` = gs_date
    WHERE
      `nameid` = gs_gameid AND
      `player` = gs_player;
  END ;;
DELIMITER ;


-- Members
DROP PROCEDURE IF EXISTS `sp_Members_AddMember`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_AddMember`(
  IN m_id INT(10),
  IN m_username VARCHAR(16),
  IN m_password VARCHAR(255),
  IN m_email VARCHAR(255),
  IN m_active VARCHAR(10),
  IN m_admin VARCHAR(10),
  IN m_ip VARCHAR(45))
  BEGIN
    INSERT INTO `members`
    (`id`,`username`,`password`,`email`,`active`,`regtime`, `admin`,`ip`)
    VALUES
      (m_id, m_username, m_password, m_email, m_active, UNIX_TIMESTAMP(NOW()), m_admin, m_ip);
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_DeleteMember`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_DeleteMember`(
  IN m_memberid INT(10),
  IN m_admin VARCHAR(10))
  BEGIN
    DELETE FROM `members`
    WHERE `id` = m_memberid
          AND `admin` = m_admin;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_EditMember_Admin`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_EditMember_Admin`(
  IN m_username VARCHAR(16),
  IN m_email VARCHAR(255),
  IN m_active VARCHAR(10),
  IN m_twitter VARCHAR(255),
  IN m_isadmin VARCHAR(10),
  IN m_memberid INT(10))
  BEGIN
    UPDATE 	`members`
    SET    	`username` = m_username,
      `email` = m_email,
      `active` = m_active,
      `twitter_id` = m_twitter,
      `admin` = m_isadmin
    WHERE  	`id` = m_memberid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GeneratePassword`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GeneratePassword`(
  IN m_username VARCHAR(16),
  IN hashedpw VARCHAR(255))
  BEGIN
    UPDATE `members`
    SET `password` = hashedpw
    WHERE `username` = m_username;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetAllIDs`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetAllIDs`()
  BEGIN
    SELECT `id`
    FROM `members`;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetAllMembers`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetAllMembers`()
  BEGIN
    SELECT `id`,`username`,`totalgames`,`ip`,`last_login`
    FROM `members`
    ORDER BY `username` ASC;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetMemberbyID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetMemberbyID`(
  IN m_id INT(10))
  BEGIN
    SELECT *
    FROM `members`
    WHERE `id` = m_id
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetPassword`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetPassword`(
  IN m_username VARCHAR(16))
  BEGIN
    SELECT `password`
    FROM `members`
    WHERE `username` = m_username;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_getUserEmail`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_getUserEmail`(
  IN m_username VARCHAR(16))
  BEGIN
    SELECT `email`
    FROM `members`
    WHERE `username` = m_username;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetUsernameAndEmail`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetUsernameAndEmail`(
  IN m_username VARCHAR(16),
  IN m_email VARCHAR(255))
  BEGIN
    SELECT `username`,`email`
    FROM `members`
    WHERE `username` = m_username
          AND `email`= m_email LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetUsernameOREmail`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetUsernameOREmail`(
  IN m_username VARCHAR(16),
  IN m_useremail VARCHAR(255))
  BEGIN
    SELECT `username`, `email`
    FROM `members`
    WHERE `username` = m_username
          OR `email` = m_useremail;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GetSession`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GetSession`(
  IN m_username VARCHAR(16))
  BEGIN
    SELECT *
    FROM `members`
    WHERE `username` = m_username
          AND `active` = 'Yes';
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_UpdateMemberPassword`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdateMemberPassword`(
  IN m_password VARCHAR(255),
  IN m_memberid INT(10))
  BEGIN
    UPDATE `members`
    SET `password` = m_password
    WHERE `id` = m_memberid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_UpdatePasswordbyUserEmail`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdatePasswordbyUserEmail`(
  IN m_password VARCHAR(255),
  IN m_username VARCHAR(16),
  IN m_useremail VARCHAR(255))
  BEGIN
    UPDATE `members`
    SET `password` = m_password
    WHERE `username` = m_username
          AND `email` = m_useremail;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_UpdateMemberProfile`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdateMemberProfile`(
  IN m_email VARCHAR(255),
  IN m_github VARCHAR(255),
  IN m_facebook VARCHAR(255),
  IN m_twitter VARCHAR(255),
  IN m_id INT(10))
  BEGIN
    UPDATE `members`
    SET
      `twitter_id` = m_twitter,
      `facebook_id` = m_facebook,
      `github_id` = m_github,
      `email` = m_email
    WHERE `id` = m_id;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_UpdatePlaycount`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdatePlaycount`(
  IN userid INT(10))
  BEGIN
    UPDATE `members`
    SET `totalgames` = `totalgames` + 1
    WHERE `id` = userid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_UpdateLastLogin`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdateLastLogin`(
  IN userid INT(10))
  BEGIN
    UPDATE `members`
    SET `last_login` = UNIX_TIMESTAMP()
    WHERE `id` = userid;
  END ;;
DELIMITER ;

-- Pages
DROP PROCEDURE IF EXISTS `sp_Pages_DeletePagebyID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Pages_DeletePagebyID`(
  IN p_pageid INT(10))
  BEGIN
    DELETE FROM `pages`
    WHERE `id` = p_pageid
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Pages_GetPagesbyPageID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Pages_GetPagesbyPageID`(
  IN p_pageid INT(10))
  BEGIN
    SELECT *
    FROM `pages`
    WHERE `id` = p_pageid
    LIMIT 1;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Pages_GetPagesbyID_ASC`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Pages_GetPagesbyID_ASC`()
  BEGIN
    SELECT *
    FROM `pages`
    WHERE `id` != ''
    ORDER BY `id` ASC;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Pages_InsertPage`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Pages_InsertPage`(
  IN p_pageid INT(10),
  IN p_pagetitle VARCHAR(255),
  IN p_pagecontent TEXT,
  IN p_pagekeywords VARCHAR(255),
  IN p_pagedescription VARCHAR(255))
  BEGIN
    INSERT INTO `pages`
      (`id`, `title`, `content`, `keywords`, `description`)
    VALUES
      (p_pageid, p_pagetitle, p_pagecontent, p_pagekeywords, p_pagedescription);
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Pages_UpdatePage`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Pages_UpdatePage`(
  IN p_pageid INT(10),
  IN p_pagetitle VARCHAR(255),
  IN p_pagecontent TEXT,
  IN p_pagekeywords VARCHAR(255),
  IN p_pagedescription VARCHAR(255))
  BEGIN
    UPDATE `pages`
    SET
      `title` = p_pagetitle,
      `content` = p_pagecontent,
      `description` = p_pagedescription,
      `keywords` = p_pagekeywords
    WHERE `id` = p_pageid;
  END ;;
DELIMITER ;
