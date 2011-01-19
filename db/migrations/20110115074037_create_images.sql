CREATE TABLE `images` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `model` varchar(255) NOT NULL,
    `foreign_key` int(12) NOT NULL,
    `path` varchar(255) DEFAULT NULL,
    `url` varchar(255) DEFAULT NULL,
    `author` varchar(255) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `filesize` int(12) DEFAULT NULL,
    `filesize_octal` int(12) DEFAULT NULL,
    `type` varchar(255) DEFAULT NULL,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `by_foreign_key` ON `images`(`foreign_key`, `model`);