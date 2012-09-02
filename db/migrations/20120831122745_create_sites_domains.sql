SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE IF NOT EXISTS `sites_domains` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(12) NOT NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `sites_domains`
  ADD CONSTRAINT `sites_domains_ibfk_2` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `sites_domains` (`site_id`, `domain`)
	SELECT `sites`.`id`, `sites`.`domain`  FROM `sites` where `sites`.`domain` IS NOT NULL;
SET FOREIGN_KEY_CHECKS=1;