CREATE TABLE `users` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `site_id` int(12) NOT NULL,
    `username` varchar(255) NOT NULL,
    `password` varchar(40) NOT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT 0,
    `token` varchar(40) NOT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `by_site` ON `users`(`site_id`);
CREATE UNIQUE INDEX `by_username_and_password` ON `users`(`username`, `password`);