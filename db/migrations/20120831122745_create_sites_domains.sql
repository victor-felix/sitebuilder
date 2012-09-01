CREATE TABLE IF NOT EXISTS `sites_domains` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(12) unsigned NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_domain` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `sites_domains` (`site_id`, `domain`)
	SELECT `sites`.`id`, `sites`.`domain`  FROM `sites` where `sites`.`domain` IS NOT NULL;