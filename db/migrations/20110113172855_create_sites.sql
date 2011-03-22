CREATE TABLE `sites` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `feed_id` int(12) DEFAULT NULL,
    `domain` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `address` text DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `phone` varchar(255) DEFAULT NULL,

    # links
    `website` varchar(255) DEFAULT NULL,
    `facebook` varchar(255) DEFAULT NULL,
    `twitter` varchar(255) DEFAULT NULL,

    # logo fields
    `logo_path` varchar(255) DEFAULT NULL,
    `logo_url` varchar(255) DEFAULT NULL,
    `logo_author` varchar(255) DEFAULT NULL,
    `logo_title` varchar(255) DEFAULT NULL,
    `logo_description` text DEFAULT NULL,
    `logo_filesize` int(12) DEFAULT NULL,
    `logo_filesize_octal` int(12) DEFAULT NULL,
    `logo_type` varchar(255) DEFAULT NULL,

    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `by_domain` ON `sites`(`domain`);