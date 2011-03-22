CREATE TABLE `categories` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `site_id` int(12) NOT NULL,
    `parent_id` int(12) NOT NULL DEFAULT 0,
    `title` varchar(255) NOT NULL,
    `order` int(12) NOT NULL DEFAULT 0,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `by_parent` ON `categories`(`site_id`, `parent_id`);