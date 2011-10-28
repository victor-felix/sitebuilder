ALTER TABLE `categories`
    ADD COLUMN `type` varchar(64) AFTER `parent_id`;
