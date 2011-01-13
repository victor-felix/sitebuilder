CREATE TABLE `articles` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `feed_id` int(12) NOT NULL,
    `guid` varchar(255) NOT NULL,
    `link` varchar(255) DEFAULT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `author` varchar(255) DEFAULT NULL,
    
    # image fields
    `image_path` varchar(255) DEFAULT NULL,
    `image_url` varchar(255) DEFAULT NULL,
    `image_author` varchar(255) DEFAULT NULL,
    `image_title` varchar(255) DEFAULT NULL,
    `image_description` text DEFAULT NULL,
    `image_filesize` int(12) DEFAULT NULL,
    `image_filesize_octal` int(12) DEFAULT NULL,
    `image_type` varchar(255) DEFAULT NULL,
    
    `pubdate` datetime DEFAULT NULL,
    `created` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `by_feed` ON `articles`(`feed_id`);
CREATE INDEX `by_guid` ON `articles`(`guid`);