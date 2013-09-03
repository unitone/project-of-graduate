-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2011 at 05:29 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `home`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `userid`) VALUES
(1, '我的新类别', 2);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `content`, `type`, `postid`, `userid`, `date`) VALUES
(4, '回复回复', 2, 4, 2, '2011-09-21 02:51:05'),
(6, '回复回复回复回复', 2, 3, 2, '2011-09-21 02:51:11'),
(12, '试一下吧', 2, 5, 2, '2011-09-24 03:02:53'),
(13, '再回一条', 2, 3, 2, '2011-10-03 06:55:59'),
(14, '555555555555', 2, 12, 2, '2011-10-03 07:19:10'),
(15, '回复日志', 1, 14, 2, '2011-10-11 09:45:50'),
(16, '再回一条看看', 1, 14, 2, '2011-10-11 10:04:10'),
(17, '这里也是能回复的', 1, 14, 2, '2011-10-11 10:12:02'),
(18, '回复一下吧', 1, 1, 2, '2011-10-13 03:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE IF NOT EXISTS `friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `friendid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `friend`
--

INSERT INTO `friend` (`id`, `userid`, `friendid`, `groupid`) VALUES
(6, 3, 2, 0),
(7, 2, 3, 0),
(8, 4, 2, 0),
(9, 2, 4, 10),
(10, 5, 2, 0),
(11, 2, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `friend_group`
--

CREATE TABLE IF NOT EXISTS `friend_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `friend_group`
--

INSERT INTO `friend_group` (`id`, `userid`, `name`) VALUES
(2, 3, '分类'),
(3, 3, '再一次分类'),
(4, 3, '再再分类'),
(5, 3, '再再再分类'),
(10, 2, '分类1');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `fromid`, `toid`, `title`, `content`, `date`) VALUES
(5, 2, 4, '<p>添加好友</p>', '<p>你好,用户1想添加你为好友，是否同意？</p><a href="friend.php?action=friend_valution&want=yes&id=2">同意</a> - <a href="friend.php?action=friend_valution&want=no&id=2">不同意</a>', '2011-09-24 03:43:58'),
(6, 2, 3, '我就试一下', '我就试一下的内容', '2011-10-03 06:08:49'),
(8, 3, 2, '再来一条又如何', '再来一条又如何的内容', '2011-10-03 06:11:04'),
(9, 2, 5, '添加好友', '你好,用户1想添加你为好友，是否同意？<a href="friend.php?action=friend_valution&want=yes&id=2">同意</a> - <a href="friend.php?action=friend_valution&want=no&id=2">不同意</a>', '2011-10-31 05:21:54');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `content` text NOT NULL,
  `userid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `authority` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `title`, `content`, `userid`, `categoryid`, `type`, `authority`, `date`) VALUES
(1, '测试文章标题', '<div style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;font:normal normal normal 12px/1.5 ''sans serif'', tahoma, verdana, helvetica;">测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容。</div>\r\n<div style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;font:normal normal normal 12px/1.5 ''sans serif'', tahoma, verdana, helvetica;"><div style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px;font:normal normal normal 12px/1.5 ''sans serif'', tahoma, verdana, helvetica;">测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容，测试文章内容。</div>\r\n</div>', 2, 0, 1, 2, '2011-09-15 13:34:28'),
(3, NULL, '心情，有木有，有木有', 2, 0, 2, 2, '2011-09-15 13:45:36'),
(4, NULL, '有木有心情，有木有', 2, 0, 2, 2, '2011-09-16 16:34:31'),
(5, NULL, '这是另一个用户的心情，有木有', 3, 0, 2, 2, '2011-09-16 16:51:59'),
(6, NULL, '这是另一个用户的第二条心情，有木有', 3, 0, 2, 2, '2011-09-16 16:52:11'),
(12, NULL, '啊啊呀，心情啊', 2, 0, 2, 2, '2011-09-21 21:00:25'),
(13, '是志，又是是志', '是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志是志，又是是志', 2, 0, 1, 2, '2011-09-22 11:16:24'),
(14, '日志啊日志', '要日志啊日志女日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志', 3, 0, 1, 2, '2011-09-24 10:49:44'),
(15, '日志啊日志', '要日志啊日志女日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志日志啊日志', 2, 0, 1, 1, '2011-10-19 15:13:02'),
(16, '类别测试', '类别测试 内容再次更新', 2, 1, 1, 1, '2011-10-23 15:26:17'),
(17, NULL, '我是用户4', 5, 0, 2, 2, '2011-10-31 13:24:32'),
(18, '用户4的文章', '用户4的文章内容用户4的<strong>文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的</strong>文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容<span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span><span style="background-color:#e53333;">用户4的文章内容</span>用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容用户4的文章内容', 5, 0, 1, 2, '2011-10-31 13:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(200) NOT NULL,
  `class` varchar(5) NOT NULL,
  `grade` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `email`, `class`, `grade`) VALUES
(2, '用户1', 'user', 'user1@user.com', '理学系', '一年级'),
(3, '用户2', 'user', 'user2@user.com', '理学系', '二年级'),
(4, '用户3', 'user', 'user3@user.com', '外语系', '四年级'),
(5, '用户4', 'user', 'user4@user.com', '外语系', '三年级');
