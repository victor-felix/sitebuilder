ALTER TABLE sites
    ADD COLUMN timezone VARCHAR(255) AFTER longitude,
    ADD COLUMN date_format VARCHAR(255) AFTER timezone;
