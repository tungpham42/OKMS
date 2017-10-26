-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 03, 2013 at 04:09 PM
-- Server version: 5.1.66-cll
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tungpham_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_COMMENT`
--

CREATE TABLE IF NOT EXISTS `OKMS_COMMENT` (
  `Comment_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Comment ID',
  `Post_ID` int(10) unsigned DEFAULT NULL COMMENT 'Page ID',
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Comment_Body` longtext COMMENT 'Comment Body',
  `Comment_Hide_Name` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hide comments?',
  `Comment_Created` int(11) NOT NULL DEFAULT '0' COMMENT 'Date created timestamp',
  `Comment_Edited` int(11) NOT NULL DEFAULT '0' COMMENT 'Date updated timestamp',
  PRIMARY KEY (`Comment_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;



--
-- Table structure for table `OKMS_COMMENT_VOTE`
--

CREATE TABLE IF NOT EXISTS `OKMS_COMMENT_VOTE` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Comment_ID` int(10) unsigned NOT NULL COMMENT 'Comment ID',
  `CommentVote_Like` int(1) NOT NULL DEFAULT '0' COMMENT 'Like Token',
  `CommentVote_Dislike` int(1) NOT NULL DEFAULT '0' COMMENT 'Dislike Token',
  KEY `User_ID` (`User_ID`),
  KEY `Comment_ID` (`Comment_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_COMMENT_VOTE`
--

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_COURSE`
--

CREATE TABLE IF NOT EXISTS `OKMS_COURSE` (
  `Course_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Course ID',
  `User_ID` int(10) unsigned DEFAULT NULL COMMENT 'Coordinator ID',
  `Course_Name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Course Name',
  `Course_Code` varchar(255) NOT NULL DEFAULT '' COMMENT 'Course Code',
  `Course_Allowed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow students to post questions?',
  `Course_For_Guest` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Allow guests to post questions?',
  PRIMARY KEY (`Course_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `OKMS_COURSE`
--

INSERT INTO `OKMS_COURSE` (`Course_ID`, `User_ID`, `Course_Name`, `Course_Code`, `Course_Allowed`) VALUES
(8, 4, 'BIS Strategy and Governance', 'ISYS2424', 1),
(2, 6, 'Project Mgmt & Prof Practice for Info Systems', 'ISYS2131', 1),
(1, 82, 'BIS Capstone Project', 'ISYS2132', 1),
(3, 43, 'Business Computing', 'ISYS2109', 0),
(5, 42, 'Internet for Business', 'ISYS2110', 0),
(12, NULL, 'E-Business Systems', 'INTE2435', 1),
(13, NULL, 'Bussiness Information System Analysis and Design 2', 'ISYS2117', 0),
(14, 3, 'Business Info Systems Development 2', 'ISYS2119', 1),
(15, 3, 'The Business IS Professional', 'ISYS3295', 0),
(22, 43, 'Intro to BIS Development', 'ISYS2115', 1),
(18, NULL, 'Client Management', 'COMM2384', 1),
(19, NULL, 'Asian Cyber Culture', 'COMM2383', 0),
(32, NULL, 'Visual Basic', 'ISYS2116', 0),
(21, NULL, 'Marketing', 'MKTG1205', 1),
(23, NULL, 'Networking in Business', 'INTE2432', 0),
(31, NULL, ' Database Fundamental Demo', 'ISYS2222', 0),
(30, 82, 'BIS Capstone Project Demo', 'ISYS2111', 1),
(33, NULL, 'Business Info System Development 1', 'ISYS2116', 0),
(34, NULL, 'Business Database 2', 'ISYS2423', 0),
(35, 53, 'Internet for Business Saigon', 'ISYS2110S', 0),
(36, 82, 'Test Course', 'ISYS2113', 0),
(37, 82, 'ERP Systems', 'ISYS2426', 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST` (
  `Post_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Post ID',
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Course_ID` int(10) unsigned NOT NULL COMMENT 'Course ID',
  `Repost_ID` int(10) unsigned DEFAULT NULL COMMENT 'Repost ID',
  `Post_Week` int(2) NOT NULL COMMENT 'Week',
  `Post_Title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Page Title',
  `Post_URL` varchar(60) NOT NULL COMMENT 'Page URL Alias',
  `Post_Question` text NOT NULL COMMENT 'Page Body',
  `Post_Answer` text NOT NULL COMMENT 'Answer',
  `Post_Hide_Name` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Hide username',
  `Post_Current` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Current Semester?',
  `Post_Created` int(11) NOT NULL DEFAULT '0' COMMENT 'Date created timestamp',
  `Post_Edited` int(11) NOT NULL DEFAULT '0' COMMENT 'Date updated timestamp',
  PRIMARY KEY (`Post_ID`),
  FULLTEXT KEY `SEARCH` (`Post_Title`,`Post_Question`,`Post_Answer`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=154 ;

--
-- Dumping data for table `OKMS_POST`
--

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST_FOLLOW`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST_FOLLOW` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Post_ID` int(10) unsigned NOT NULL COMMENT 'Post ID',
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_POST_FOLLOW`
--

--
-- Table structure for table `OKMS_POST_RATE`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST_RATE` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Post_ID` int(10) unsigned NOT NULL COMMENT 'Post ID',
  `PostRate` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Rate Token',
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_POST_RATE`
--

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_POST_VOTE`
--

CREATE TABLE IF NOT EXISTS `OKMS_POST_VOTE` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Post_ID` int(10) unsigned NOT NULL COMMENT 'Post ID',
  `PostVote_Like` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Like Token',
  `PostVote_Dislike` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Dislike Token',
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_POST_VOTE`
--

------------------------------------

--
-- Table structure for table `OKMS_ROLE`
--

CREATE TABLE IF NOT EXISTS `OKMS_ROLE` (
  `Role_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Role ID',
  `Role_Name` varchar(60) NOT NULL DEFAULT '' COMMENT 'Role Name',
  PRIMARY KEY (`Role_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `OKMS_ROLE`
--

INSERT INTO `OKMS_ROLE` (`Role_ID`, `Role_Name`) VALUES
(1, 'System Admin'),
(2, 'Student'),
(3, 'Lecturer');

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_SEMESTER`
--

CREATE TABLE IF NOT EXISTS `OKMS_SEMESTER` (
  `Semester_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Semester_Code` varchar(255) NOT NULL,
  `Semester_Start_Date` int(11) NOT NULL DEFAULT '0',
  `Semester_End_Date` int(11) NOT NULL DEFAULT '0',
  `Semester_Current` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Semester_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `OKMS_SEMESTER`
--

INSERT INTO `OKMS_SEMESTER` (`Semester_ID`, `Semester_Code`, `Semester_Start_Date`, `Semester_End_Date`, `Semester_Current`) VALUES
(1, '2012B', 1339952400, 1349629200, 0),
(2, '2012A', 1329670800, 1339347600, 0),
(3, '2012C', 1350234000, 1359910800, 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_USER`
--

CREATE TABLE IF NOT EXISTS `OKMS_USER` (
  `User_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'User ID',
  `Role_ID` int(10) unsigned NOT NULL COMMENT 'Role ID',
  `User_Alias` varchar(32) DEFAULT NULL COMMENT 'User Alias',
  `User_Username` varchar(60) NOT NULL DEFAULT '' COMMENT 'User Name',
  `User_Fullname` varchar(255) NOT NULL DEFAULT '' COMMENT 'Fullname',
  `User_Password` varchar(32) NOT NULL DEFAULT '' COMMENT 'Password',
  `User_Mail` varchar(64) DEFAULT NULL COMMENT 'Email',
  `User_Created` int(11) NOT NULL DEFAULT '0' COMMENT 'Date created timestamp',
  `User_Hash` varchar(32) NOT NULL COMMENT 'Verify email hash',
  `User_Status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'User status',
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `User_Username` (`User_Username`),
  UNIQUE KEY `User_Mail` (`User_Mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=88 ;

--
-- Dumping data for table `OKMS_USER`
--

INSERT INTO `OKMS_USER` (`User_ID`, `Role_ID`, `User_Alias`, `User_Username`, `User_Fullname`, `User_Password`, `User_Mail`, `User_Created`, `User_Hash`, `User_Status`) VALUES
(1, 1, NULL, 'admin', 'System Administrator', 'df67be76ff44a34799a1616443d56776', 'tung.42@gmail.com', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `OKMS_USER_COURSE`
--

CREATE TABLE IF NOT EXISTS `OKMS_USER_COURSE` (
  `Course_ID` int(10) unsigned NOT NULL COMMENT 'Course ID',
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  KEY `Course_ID` (`Course_ID`),
  KEY `User_ID` (`User_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_USER_COURSE`

---- --------------------------------------------------------

--
-- Table structure for table `OKMS_USER_FOLLOW`
--

CREATE TABLE IF NOT EXISTS `OKMS_USER_FOLLOW` (
  `User_ID` int(10) unsigned NOT NULL COMMENT 'User ID',
  `Followee_ID` int(10) unsigned NOT NULL COMMENT 'Followee ID',
  KEY `User_ID` (`User_ID`),
  KEY `Followee_ID` (`Followee_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `OKMS_USER_FOLLOW`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
