ALTER TABLE sites
    CHANGE COLUMN state state_id INT(12),
    CHANGE COLUMN country country_id INT(12);
