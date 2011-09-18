-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 02, 2011 at 11:34 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `object` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `date_time` datetime NOT NULL,
  `ip_addr` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `attachments`
--


-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `languages` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` VALUES(1, 'en');

-- --------------------------------------------------------

--
-- Table structure for table `objects`
--

CREATE TABLE `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rewrite` varchar(255) NOT NULL,
  `ordering` int(6) NOT NULL,
  `dateSaved` datetime NOT NULL,
  `ip_addr` varchar(15) NOT NULL,
  `data` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fullUrl` varchar(255) NOT NULL,
  `alt_title` varchar(255) NOT NULL,
  `alt_description` text NOT NULL,
  `showonmenu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `objects`
--

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `ident` varchar(60) NOT NULL,
  `values` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` VALUES(6, 1, 'siteTitle', 'a:1:{s:2:"en";s:15:"";}');
INSERT INTO `translations` VALUES(27, 1, 'defaultSiteDescription', 'a:2:{s:2:"ee";s:0:"";s:2:"en";s:0:"";}');

-- --------------------------------------------------------

--
-- Table structure for table `translationsGroups`
--

CREATE TABLE `translationsGroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `translationsGroups`
--

INSERT INTO `translationsGroups` VALUES(1, 'site');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(6) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, 1, 'youremail@host.com', SHA1('yourpassword'), 'content', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usersGroups`
--

CREATE TABLE `usersGroups` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `usersGroups`
--

INSERT INTO `usersGroups` VALUES(1, 'administrator');
INSERT INTO `usersGroups` VALUES(2, 'users');

-- --------------------------------------------------------

--
-- Table structure for table `usersPermissions`
--

CREATE TABLE `usersPermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(6) NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `usersPermissions`
--

INSERT INTO `usersPermissions` VALUES(1, 2, 'content', 'read', 1);
INSERT INTO `usersPermissions` VALUES(3, 2, 'configuration', 'read', 1);
INSERT INTO `usersPermissions` VALUES(4, 2, 'groups', 'read', 1);
INSERT INTO `usersPermissions` VALUES(5, 2, 'permissions', 'read', 1);
INSERT INTO `usersPermissions` VALUES(6, 2, 'users', 'read', 1);
INSERT INTO `usersPermissions` VALUES(7, 2, 'translations', 'read', 1);
