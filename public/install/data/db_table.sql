-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-12-11 10:46:39
-- 服务器版本： 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo.qingcms.com`
--

-- --------------------------------------------------------

--
-- 表的结构 `pre_ad`
--

DROP TABLE IF EXISTS `pre_ad`;
CREATE TABLE `pre_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `content` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_closable` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` int(11) DEFAULT NULL,
  `stime` int(11) DEFAULT NULL,
  `etime` int(11) DEFAULT NULL,
  `display_order` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_area`
--

DROP TABLE IF EXISTS `pre_area`;
CREATE TABLE `pre_area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_comment`
--

DROP TABLE IF EXISTS `pre_comment`;
CREATE TABLE `pre_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `reply_uid` int(11) NOT NULL,
  `reply_comment_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `ctime` int(11) NOT NULL,
  `position` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_content`
--

DROP TABLE IF EXISTS `pre_content`;
CREATE TABLE `pre_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `content` char(255) NOT NULL DEFAULT '',
  `cateid` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `type_data` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `pic_path` varchar(120) NOT NULL,
  `pic_name` varchar(120) NOT NULL,
  `video` text NOT NULL,
  `up` int(11) NOT NULL DEFAULT '0',
  `down` int(11) NOT NULL DEFAULT '0',
  `comment` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) NOT NULL DEFAULT '0',
  `is_check` tinyint(1) DEFAULT NULL,
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已经删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_content_cate`
--

DROP TABLE IF EXISTS `pre_content_cate`;
CREATE TABLE `pre_content_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) NOT NULL DEFAULT '0',
  `nid` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `type_id` tinyint(1) NOT NULL,
  `def` tinyint(1) NOT NULL,
  `isnav` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid` (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_content_type`
--

DROP TABLE IF EXISTS `pre_content_type`;
CREATE TABLE `pre_content_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_credit_rule`
--

DROP TABLE IF EXISTS `pre_credit_rule`;
CREATE TABLE `pre_credit_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rulename` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `cycletype` tinyint(1) NOT NULL DEFAULT '0',
  `cyclenum` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `gold` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `action` (`action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_credit_user`
--

DROP TABLE IF EXISTS `pre_credit_user`;
CREATE TABLE `pre_credit_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_digg`
--

DROP TABLE IF EXISTS `pre_digg`;
CREATE TABLE `pre_digg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  `vote` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_favorite`
--

DROP TABLE IF EXISTS `pre_favorite`;
CREATE TABLE `pre_favorite` (
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL,
  PRIMARY KEY (`uid`,`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_feedback`
--

DROP TABLE IF EXISTS `pre_feedback`;
CREATE TABLE `pre_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `data` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_follow`
--

DROP TABLE IF EXISTS `pre_follow`;
CREATE TABLE `pre_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_follow_group`
--

DROP TABLE IF EXISTS `pre_follow_group`;
CREATE TABLE `pre_follow_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_follow_link`
--

DROP TABLE IF EXISTS `pre_follow_link`;
CREATE TABLE `pre_follow_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`fid`,`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_friendlink`
--

DROP TABLE IF EXISTS `pre_friendlink`;
CREATE TABLE `pre_friendlink` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `logo` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(3) NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_message`
--

DROP TABLE IF EXISTS `pre_message`;
CREATE TABLE `pre_message` (
  `uid` int(11) NOT NULL,
  `notify` mediumint(6) NOT NULL,
  `comment` mediumint(6) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_nav`
--

DROP TABLE IF EXISTS `pre_nav`;
CREATE TABLE `pre_nav` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `nid` varchar(120) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `displayorder` tinyint(3) NOT NULL,
  `disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_notify`
--

DROP TABLE IF EXISTS `pre_notify`;
CREATE TABLE `pre_notify` (
  `notify_id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `receive` int(11) NOT NULL,
  `type` char(80) NOT NULL,
  `data` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` int(11) NOT NULL,
  PRIMARY KEY (`notify_id`),
  KEY `receive` (`receive`,`is_read`),
  KEY `ctime` (`ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_plugin`
--

DROP TABLE IF EXISTS `pre_plugin`;
CREATE TABLE `pre_plugin` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `zhName` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `info` tinytext,
  `version` varchar(50) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `site` varchar(255) DEFAULT NULL,
  `qcVersion` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_plugin_data`
--

DROP TABLE IF EXISTS `pre_plugin_data`;
CREATE TABLE `pre_plugin_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` char(30) NOT NULL,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plugin` (`plugin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_site`
--

DROP TABLE IF EXISTS `pre_site`;
CREATE TABLE `pre_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL DEFAULT '',
  `url` text NOT NULL,
  `class` int(11) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `good` tinyint(1) NOT NULL DEFAULT '0',
  `good2` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_smiley`
--

DROP TABLE IF EXISTS `pre_smiley`;
CREATE TABLE `pre_smiley` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'miniblog',
  `code` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `displayorder` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_system`
--

DROP TABLE IF EXISTS `pre_system`;
CREATE TABLE `pre_system` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `list` char(30) DEFAULT 'default',
  `keyword` char(50) DEFAULT 'default',
  `value` text,
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `list` (`list`,`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_tag`
--

DROP TABLE IF EXISTS `pre_tag`;
CREATE TABLE `pre_tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `tag_name` varchar(120) NOT NULL,
  `ctime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_user`
--

DROP TABLE IF EXISTS `pre_user`;
CREATE TABLE `pre_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '1',
  `location` varchar(255) NOT NULL,
  `zonename` varchar(255) DEFAULT NULL,
  `zonetitle` varchar(255) DEFAULT NULL,
  `is_admin` int(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `ctime` int(11) DEFAULT NULL,
  `lastvisit` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `t_weibo` char(255) NOT NULL DEFAULT '',
  `t_qq` char(255) NOT NULL DEFAULT '',
  `domain` char(80) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_user_field`
--

DROP TABLE IF EXISTS `pre_user_field`;
CREATE TABLE `pre_user_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(120) NOT NULL,
  `name` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `module` varchar(60) NOT NULL,
  `displayorder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_user_profile`
--

DROP TABLE IF EXISTS `pre_user_profile`;
CREATE TABLE `pre_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `contact` longtext,
  `intro` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_weibo`
--

DROP TABLE IF EXISTS `pre_weibo`;
CREATE TABLE `pre_weibo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `ctime` int(11) NOT NULL,
  `from` tinyint(1) NOT NULL,
  `comment` mediumint(8) NOT NULL,
  `transpond_id` int(11) NOT NULL DEFAULT '0',
  `transpond` mediumint(8) NOT NULL,
  `type` varchar(255) DEFAULT '0',
  `type_data` text,
  `from_data` text,
  `isdel` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pre_weibo_comment`
--

DROP TABLE IF EXISTS `pre_weibo_comment`;
CREATE TABLE `pre_weibo_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `wid` int(11) NOT NULL,
  `reply_uid` int(11) NOT NULL,
  `reply_comment_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `ctime` int(11) NOT NULL,
  `position` int(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
