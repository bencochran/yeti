-- MySQL dump 10.11
--
-- Host: localhost    Database: xbt
-- ------------------------------------------------------
-- Server version	5.0.32-Debian_7etch5-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `xbt_categories`
--

DROP TABLE IF EXISTS `xbt_categories`;
CREATE TABLE `xbt_categories` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `slug` varchar(255) default NULL,
  PRIMARY KEY  (`cid`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `xbt_comments`
--

DROP TABLE IF EXISTS `xbt_comments`;
CREATE TABLE `xbt_comments` (
  `cid` int(11) NOT NULL auto_increment,
  `file_fid` int(11) NOT NULL,
  `user_uid` int(11) NOT NULL,
  `body` text,
  `ctime` int(11) NOT NULL,
  PRIMARY KEY  (`cid`),
  KEY `file_fid` (`file_fid`),
  KEY `user_uid` (`user_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Table structure for table `xbt_config`
--

DROP TABLE IF EXISTS `xbt_config`;
CREATE TABLE `xbt_config` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `xbt_deny_from_hosts`
--

DROP TABLE IF EXISTS `xbt_deny_from_hosts`;
CREATE TABLE `xbt_deny_from_hosts` (
  `begin` int(11) NOT NULL,
  `end` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `xbt_email_exceptions`
--

DROP TABLE IF EXISTS `xbt_email_exceptions`;
CREATE TABLE `xbt_email_exceptions` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `allow` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `xbt_files`
--

DROP TABLE IF EXISTS `xbt_files`;
CREATE TABLE `xbt_files` (
  `fid` int(11) NOT NULL auto_increment,
  `info_hash` blob,
  `leechers` int(11) NOT NULL default '0',
  `seeders` int(11) NOT NULL default '0',
  `completed` int(11) NOT NULL default '0',
  `flags` int(11) NOT NULL default '0',
  `mtime` int(11) default NULL,
  `ctime` int(11) default NULL,
  `path` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `description` text,
  `size` bigint(20) unsigned default NULL,
  `user_uid` int(10) unsigned default NULL,
  `category_cid` int(10) unsigned default NULL,
  PRIMARY KEY  (`fid`),
  UNIQUE KEY `info_hash` (`info_hash`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Table structure for table `xbt_files_users`
--

DROP TABLE IF EXISTS `xbt_files_users`;
CREATE TABLE `xbt_files_users` (
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `announced` int(11) NOT NULL,
  `completed` int(11) NOT NULL,
  `downloaded` bigint(20) NOT NULL,
  `left` bigint(20) NOT NULL,
  `uploaded` bigint(20) NOT NULL,
  `mtime` int(11) NOT NULL,
  UNIQUE KEY `fid` (`fid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `xbt_messages`
--

DROP TABLE IF EXISTS `xbt_messages`;
CREATE TABLE `xbt_messages` (
  `mid` int(11) NOT NULL auto_increment,
  `from_user_uid` int(11) NOT NULL,
  `to_user_uid` int(11) NOT NULL,
  `subject` varchar(255) default NULL,
  `body` text,
  `ctime` int(11) NOT NULL,
  `is_read` int(11) NOT NULL default '0',
  PRIMARY KEY  (`mid`),
  KEY `to_user_uid` (`to_user_uid`),
  KEY `from_user_uid` (`from_user_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `xbt_news`
--

DROP TABLE IF EXISTS `xbt_news`;
CREATE TABLE `xbt_news` (
  `nid` int(11) NOT NULL auto_increment,
  `user_uid` int(11) NOT NULL,
  `title` varchar(255) default NULL,
  `body` text,
  `is_visible` int(11) NOT NULL default '0',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY  (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `xbt_paths`
--

DROP TABLE IF EXISTS `xbt_paths`;
CREATE TABLE `xbt_paths` (
  `pid` bigint(20) unsigned NOT NULL auto_increment,
  `torrent_fid` bigint(20) unsigned NOT NULL,
  `path` text NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `xbt_users`
--

DROP TABLE IF EXISTS `xbt_users`;
CREATE TABLE `xbt_users` (
  `uid` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) default NULL,
  `pass` blob NOT NULL,
  `ctime` int(11) NOT NULL,
  `last_seen` int(11) NOT NULL default '0',
  `can_leech` tinyint(4) NOT NULL default '1',
  `wait_time` int(11) NOT NULL,
  `peers_limit` int(11) NOT NULL,
  `torrents_limit` int(11) NOT NULL,
  `torrent_pass` char(32) NOT NULL,
  `torrent_pass_secret` bigint(20) NOT NULL,
  `downloaded` bigint(20) NOT NULL,
  `uploaded` bigint(20) NOT NULL,
  `code` varchar(255) default NULL,
  `is_activated` tinyint(3) unsigned default '0',
  `reset_code` varchar(255) default NULL,
  `wants_reset` tinyint(3) unsigned default '0',
  `wants_reset_at` bigint(20) unsigned default '0',
  `shown` tinyint(3) NOT NULL default '1',
  `admin` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-02-16 17:07:26
