-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2014 at 09:05 AM
-- Server version: 5.1.61
-- PHP Version: 5.3.6-13ubuntu3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hunbun`
--

-- --------------------------------------------------------

--
-- Table structure for table `xsocilaApp_activities`
--

CREATE TABLE IF NOT EXISTS `xsocilaApp_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_member_id` int(11) NOT NULL,
  `related_member_id` int(11) DEFAULT NULL,
  `related_activity_id` int(11) DEFAULT NULL,
  `original_activity_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `img_id` int(11) DEFAULT NULL,
  `activity_detail` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `visibility` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `xsocilaApp_activities`
--

INSERT INTO `xsocilaApp_activities` (`id`, `from_member_id`, `related_member_id`, `related_activity_id`, `original_activity_id`, `name`, `img_id`, `activity_detail`, `video_url`, `visibility`, `activity_type`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, 'Status Update', NULL, 'Hi there', '', 'Public', 'StatusUpdate', '2014-03-31 00:00:00', '2014-03-02 00:00:00'),
(2, 1, NULL, NULL, NULL, 'Comment', NULL, 'Hi there Too', '', 'Public', 'Comment', '2014-03-02 00:00:00', '2014-03-02 00:00:00'),
(3, 1, NULL, NULL, NULL, 'Comment', NULL, 'Hi there Three', '', 'Public', 'Comment', '2014-03-02 00:00:00', '2014-03-02 00:00:00'),
(4, 0, NULL, 1, NULL, '', NULL, '', '', '', 'Like', '2014-03-02 16:40:34', '2014-03-02 16:40:34');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
