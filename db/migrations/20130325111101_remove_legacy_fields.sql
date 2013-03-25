ALTER TABLE sites
    DROP COLUMN country_id,
    DROP COLUMN state_id,
    DROP COLUMN widget_id,
    DROP COLUMN user_id,
    DROP COLUMN hide_categories;
DROP TABLE countries;
DROP TABLE states;
