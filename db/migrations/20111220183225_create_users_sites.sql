CREATE TABLE IF NOT EXISTS `users_sites` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) unsigned NOT NULL,
  `site_id` int(12) unsigned NOT NULL,
  `segment` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users_sites` ADD UNIQUE `user_site_unique` ( `user_id` , `site_id` );
