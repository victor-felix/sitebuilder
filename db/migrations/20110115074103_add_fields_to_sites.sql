ALTER TABLE `sites`
    ADD COLUMN `segment` varchar(64) AFTER `feed_id`,
    ADD COLUMN `latitude` varchar(255) AFTER `twitter`,
    ADD COLUMN `longitutde` varchar(255) AFTER `latitude`,
    DROP COLUMN `logo_path`,
    DROP COLUMN `logo_url`,
    DROP COLUMN `logo_author`,
    DROP COLUMN `logo_title`,
    DROP COLUMN `logo_description`,
    DROP COLUMN `logo_filesize`,
    DROP COLUMN `logo_filesize_octal`,
    DROP COLUMN `logo_type`;