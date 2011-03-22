CREATE TABLE `feeds` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `link` varchar(255) NOT NULL,
    `updated` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;