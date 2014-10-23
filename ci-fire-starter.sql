-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2014 at 04:31 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.20

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ci-fire-starter`
--

-- --------------------------------------------------------

--
-- Table structure for table `captcha`
--

CREATE TABLE `captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `word` varchar(20) NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(256) NOT NULL,
  `title` varchar(128) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL,
  `read` datetime DEFAULT NULL,
  `read_by` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`email`,`title`,`created`,`read`,`read_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `name`, `email`, `title`, `message`, `created`, `read`, `read_by`) VALUES
(1, 'John Doe', 'john@doe.com', 'Test Message', 'This is only a test message. Notice that once you''ve read it, the button changes from blue to grey, indicating that it has been reviewed.', '2013-01-01 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `name`) VALUES
(1, 'Admin Main Menu'),
(2, 'Main Menu');

-- --------------------------------------------------------

--
-- Table structure for table `navs`
--

CREATE TABLE `navs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `type` enum('public','private','admin') NOT NULL DEFAULT 'public',
  `sort_order` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `parent_id` (`parent_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `navs`
--

INSERT INTO `navs` (`id`, `menu_id`, `parent_id`, `title`, `url`, `type`, `sort_order`) VALUES
(1, 2, 0, 'Home', '/', 'public', 1),
(2, 2, 0, 'Profile', '/profile', 'private', 10),
(3, 1, 0, 'Dashboard', '/admin', 'admin', 1),
(4, 1, 0, 'Users', '#', 'admin', 100),
(5, 1, 4, 'User List', '/admin/users', 'admin', 10),
(6, 1, 4, 'Add User', '/admin/users/add', 'admin', 20),
(7, 1, 0, 'Settings', '/admin/settings', 'admin', 255),
(8, 2, 0, 'Contact', '/contact', 'public', 50),
(9, 1, 0, 'Messages', '/admin/messages', 'admin', 150);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `name` varchar(128) NOT NULL,
  `input_type` enum('input','textarea','radio','dropdown','timezones') NOT NULL,
  `options` text COMMENT 'Use for radio and dropdown: key|value on each line',
  `is_numeric` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'forces numeric keypad on mobile devices',
  `show_editor` enum('0','1') NOT NULL DEFAULT '0',
  `input_size` enum('large','medium','small') DEFAULT NULL,
  `help_text` varchar(256) DEFAULT NULL,
  `validation` varchar(128) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL,
  `label` varchar(128) NOT NULL,
  `value` text,
  `last_update` datetime DEFAULT NULL,
  `updated_by` int(11) unsigned DEFAULT NULL,
  KEY `name` (`name`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `input_type`, `options`, `is_numeric`, `show_editor`, `input_size`, `help_text`, `validation`, `sort_order`, `label`, `value`, `last_update`, `updated_by`) VALUES
('site_name', 'input', NULL, '0', '0', 'large', NULL, 'trim|xss_clean|required|min_length[3]|max_length[128]', 10, 'Site Name', 'CI Fire Starter', '2013-01-01 00:00:00', 1),
('per_page_limit', 'dropdown', '10|10\r\n25|25\r\n50|50\r\n75|75\r\n100|100', '1', '0', 'small', NULL, 'trim|xss_clean|required|numeric', 50, 'Items Per Page', '10', '2013-01-01 00:00:00', 1),
('meta_keywords', 'input', NULL, '0', '0', 'large', 'Comma-seperated list of site keywords', 'trim|xss_clean', 20, 'Meta Keywords', 'these, are, keywords', '2013-01-01 00:00:00', 1),
('meta_description', 'textarea', NULL, '0', '0', 'large', 'Short description describing your site.', 'trim|xss_clean', 30, 'Meta Description', 'This is the site description.', '2013-01-01 00:00:00', 1),
('site_email', 'input', NULL, '0', '0', 'medium', 'Email address all emails will be sent from.', 'trim|xss_clean|required|valid_email', 40, 'Site Email', 'youremail@yourdomain.com', '2013-01-01 00:00:00', 1),
('timezones', 'timezones', NULL, '0', '0', 'medium', NULL, 'trim|xss_clean|required', 60, 'Timezone', 'UTC', '2013-01-01 00:00:00', 1),
('welcome_message', 'textarea', NULL, '0', '1', 'large', 'Message to display on home page.', 'trim|xss_clean', 70, 'Welcome Message', '<p>The page you are looking at is being generated <em>dynamically</em>. <strong>This text is editable in the admin settings.</strong></p>', '2013-01-01 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(256) NOT NULL,
  `is_admin` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `validation_code` varchar(50) DEFAULT NULL COMMENT 'Temporary code for opt-in registration',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `first_name`, `last_name`, `email`, `is_admin`, `status`, `deleted`, `validation_code`, `created`, `updated`) VALUES
(1, 'admin', 'ce516f215aa468c376736c9013e8b663f7b3c06226a87739bc6b32882f9278349a721ea725a156eecf9e3c1868904a77e4d42c783e0287a0909a8bbb97e1525f', '66cb0ab1d9efe250b46e28ecb45eb33b3609f1efda37547409a113a2b84c3f94b6a0e738acc391e2dfa718676aa55adead05fcb12d2e32aee379e190511a3252', 'Site', 'Administrator', 'admin@admin.com', '1', '1', '0', NULL, '2013-01-01 00:00:00', '2013-01-01 00:00:00'),
(2, 'johndoe', '4e8ece39c9905fe6989022a7747d2c67d90582cdf483d762905f026b3f2328dbc019acf59f77a57472228933c33429de859210a3c6b2976234501462994cf73c', 'a876126be616f492fa9ff8fb554eadffb8e43ed9faef8e1070c2508d76c57b1613899ceb97972f7959c4c05617ce36e25ba63787a8bd3f183680863c687a7c12', 'John', 'Doe', 'john@doe.com', '0', '1', '0', NULL, '2013-01-01 00:00:00', '2013-01-01 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
