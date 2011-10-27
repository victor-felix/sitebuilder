ALTER TABLE categories
    ADD COLUMN populate VARCHAR(8) DEFAULT 'manual' AFTER visibility;

UPDATE categories
    SET populate = 'auto'
    WHERE feed_url IS NOT NULL AND feed_url <> '';
