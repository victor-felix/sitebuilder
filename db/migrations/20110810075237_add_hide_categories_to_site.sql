ALTER TABLE `sites`
    ADD COLUMN hide_categories TINYINT(1) DEFAULT 0 AFTER `widget_id`;
