ALTER TABLE `sites`
    DROP COLUMN `address`,
    ADD COLUMN `street` varchar(255) AFTER `description`,
    ADD COLUMN `number` varchar(12) AFTER `street`,
    ADD COLUMN `zip` varchar(12) AFTER `number`,
    ADD COLUMN `complement` varchar(128) AFTER `zip`,
    ADD COLUMN `zone` varchar(128) AFTER `complement`,
    ADD COLUMN `city` varchar(128) AFTER `zone`,
    ADD COLUMN `state` varchar(128) AFTER `city`,
    ADD COLUMN `country` varchar(128) AFTER `state`;