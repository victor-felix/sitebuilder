SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `site_id` int(12) NOT NULL,
  `parent_id` int(12) DEFAULT NULL,
  `type` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `feed_url` varchar(255) DEFAULT NULL,
  `visibility` tinyint(4) DEFAULT '1',
  `populate` varchar(8) DEFAULT 'manual',
  `notification` tinyint(1) DEFAULT NULL,
  `icon` tinyint(1) DEFAULT NULL,
  `order` int(12) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `by_parent` (`site_id`,`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `filesize` int(12) DEFAULT NULL,
  `filesize_octal` int(12) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `by_foreign_key` (`foreign_key`,`model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `schema_migrations` (
  `version` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `segment` varchar(64) DEFAULT NULL,
  `theme` varchar(64) DEFAULT NULL,
  `skin` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `timetable` text,
  `address` text,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `stock_symbols` varchar(255) DEFAULT NULL,
  `android_app_id` varchar(255) DEFAULT NULL,
  `ios_app_id` varchar(255) DEFAULT NULL,
  `pushwoosh_app_id` varchar(50) DEFAULT NULL,
  `latest_app_version` varchar(20) DEFAULT NULL,
  `google_analytics` varchar(50) DEFAULT NULL,
  `css_token` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `date_format` varchar(255) DEFAULT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'auto',
  `landing_page` varchar(10) NOT NULL DEFAULT 'free',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `by_domain` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sites_domains` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(12) NOT NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(40) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'pt-BR',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_sites` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `site_id` int(12) unsigned NOT NULL,
  `segment` varchar(64) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '1',
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_site_unique` (`user_id`,`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `sites_domains`
  ADD CONSTRAINT `sites_domains_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
