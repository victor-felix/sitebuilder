CREATE TABLE `business_items` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `site_id` int(12) NOT NULL,
    `parent_id` int(12) NOT NULL DEFAULT 0,
    `type` varchar(64) NOT NULL,
    `order` int(12) NOT NULL DEFAULT 0,
    `created` datetime NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `business_items_values` (
    `id` int(12) NOT NULL AUTO_INCREMENT,
    `item_id` int(12) NOT NULL,
    `field` varchar(64) NOT NULL,
    `value` varchar(64) NOT NULL,
    `modified` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX `by_category` ON `business_items`(`site_id`, `parent_id`);
CREATE INDEX `by_business_item` ON `business_items_values`(`item_id`);