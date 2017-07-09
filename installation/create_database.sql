-- Host: localhost    Database: phparcade

SET UNIQUE_CHECKS=1;
SET FOREIGN_KEY_CHECKS=1;
SET SQL_MODE='NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES';

CREATE SCHEMA IF NOT EXISTS `phparcade`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

--
-- Table structure for table `ads`
--

CREATE TABLE IF NOT EXISTS`ads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `code` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `advertisername` varchar(255) NOT NULL DEFAULT '',
  `adcomment` text NOT NULL
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
INSERT INTO `phparcade`.`config` SET `key`='displaycattype',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='displaygamenum',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='dispuserlist',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='disqus_on',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='disqus_user',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='emailactivation',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='emaildebug',`value`='0';
INSERT INTO `phparcade`.`config` SET `key`='emaildomain',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='emailfrom',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='emailhost',`value`='smtp.gmail.com';
INSERT INTO `phparcade`.`config` SET `key`='emailport',`value`='587';
INSERT INTO `phparcade`.`config` SET `key`='facebook_on',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='facebook_pageurl',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='gamesperpage',`value`='18';
INSERT INTO `phparcade`.`config` SET `key`='ga_enabled', `value` ='off';
INSERT INTO `phparcade`.`config` SET `key`='ga_id', `value` ='';
INSERT INTO `phparcade`.`config` SET `key`='google_recaptcha_secretkey', `value` ='';
INSERT INTO `phparcade`.`config` SET `key`='google_recaptcha_sitekey', `value` ='';
INSERT INTO `phparcade`.`config` SET `key`='google_search_ID',`value`='8727545858461215:8837170480';
INSERT INTO `phparcade`.`config` SET `key`='highscoresenabled',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='imgurl',`value`='http://localhost/img/';
INSERT INTO `phparcade`.`config` SET `key`='loginrequired',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='memberlanguage',`value`='0';
INSERT INTO `phparcade`.`config` SET `key`='membersenabled',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='metadesc',`value`='PHPArcade is a free, open source (FOSS), online flash game arcade script. Download the GitHub script now to set up your own HTML5 and Flash game website for free!';
INSERT INTO `phparcade`.`config` SET `key`='metakey',`value`='Free,online,game,arcade,action,adventure,arcade,casino,card,driving,flying,shooting, simulation,sports,puzzle,strategy,racing,word';
INSERT INTO `phparcade`.`config` SET `key`='order',`value`='name';
INSERT INTO `phparcade`.`config` SET `key`='passwordrecovery',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='rssenabled',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='rssfeed',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='rssnumlatest',`value`='15';
INSERT INTO `phparcade`.`config` SET `key`='sitetitle',`value`='phpArcade, Free Online Script';
INSERT INTO `phparcade`.`config` SET `key`='sort',`value`='ASC';
INSERT INTO `phparcade`.`config` SET `key`='spotim_on',`value`='on';
INSERT INTO `phparcade`.`config` SET `key`='spotim_id',`value`='';
INSERT INTO `phparcade`.`config` SET `key`='theight',`value`='200';
INSERT INTO `phparcade`.`config` SET `key`='theme',`value`='responsive';
INSERT INTO `phparcade`.`config` SET `key`='twidth',`value`='200';
INSERT INTO `phparcade`.`config` SET `key`='twitter_on',`value`='off';
INSERT INTO `phparcade`.`config` SET `key`='twitter_username',`value`='';

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
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
  PRIMARY KEY (`nameid`)
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
  `regtime` int(10) NOT NULL DEFAULT 0,
  `totalgames` int(10) NOT NULL DEFAULT 0,
  `aim` varchar(255) NOT NULL DEFAULT '',
  `facebook_id` varchar(255) DEFAULT NULL,
  `github_id` varchar(255) DEFAULT NULL,
  `msn` varchar(255) NOT NULL DEFAULT '',
  `twitter_id` varchar(255) NOT NULL DEFAULT '',
  `avatarurl` varchar(255) NOT NULL DEFAULT '',
  `admin` varchar(10) NOT NULL DEFAULT 'No',
  `favorites` varchar(1) NOT NULL DEFAULT '0',
  `ip` varchar(45) NOT NULL,
  `birth_date` varchar(10) NOT NULL DEFAULT '{null}',
  `last_login` date NOT NULL DEFAULT '1970-01-01',
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
  `regtime` = 1219016824,
  `totalgames` = 0,
  `aim` = '',
  `msn` = '',
  `twitter_id` = '',
  `github_id` = NULL,
  `facebook_id` = NULL,
  `avatarurl` = '',
  `admin` = 'Yes',
  `favorites` = '',
  `ip` = '',
  `birth_date` = '',
  `last_login` = '1970-01-01';

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

DROP PROCEDURE IF EXISTS `sp_Categories_GetCategoryMaxID`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Categories_GetCategoryMaxID`()
  BEGIN
    SELECT MAX(`order`)
      AS maxOrder
    FROM `categories`;
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
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Games_GetGamebyNameid`()
  BEGIN
    SELECT `nameid`
    FROM `games`;
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

-- Games_Score
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

-- Members
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
  IN m_aim VARCHAR(255),
  IN m_msn VARCHAR(255),
  IN m_isadmin VARCHAR(10),
  IN m_memberid INT(10))
  BEGIN
    UPDATE 	`members`
    SET    	`username` = m_username,
      `email` = m_email,
      `active` = m_active,
      `twitter_id` = m_twitter,
      `aim` = m_aim,
      `msn` = m_msn,
      `admin` = m_isadmin
    WHERE  	`id` = m_memberid;
  END ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_Members_GeneratePassword`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_GeneratePassword`(
  IN hashedpw VARCHAR(255),
  IN m_username VARCHAR(16))
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

DROP PROCEDURE IF EXISTS `sp_Members_UpdateAvatar`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdateAvatar`(
  IN m_id INT(10),
  IN m_avatar VARCHAR(255))
  BEGIN
    UPDATE `members`
    SET `avatarurl` = m_avatar
    WHERE `id` = m_id;
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

DROP PROCEDURE IF EXISTS `sp_Members_UpdateMemberProfile`;
DELIMITER ;;
CREATE DEFINER=`phparcade`@`localhost` PROCEDURE `sp_Members_UpdateMemberProfile`(
  IN m_aim VARCHAR(255),
  IN m_email VARCHAR(255),
  IN m_github VARCHAR(255),
  IN m_facebook VARCHAR(255),
  IN m_msn VARCHAR(255),
  IN m_twitter VARCHAR(255),
  IN m_id INT(10))
  BEGIN
    UPDATE `members`
    SET `aim` = m_aim,
      `msn` = m_msn,
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

USE `phparcade`;