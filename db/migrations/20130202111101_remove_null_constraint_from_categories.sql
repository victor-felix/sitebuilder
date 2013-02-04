ALTER TABLE categories MODIFY COLUMN parent_id INT(12) DEFAULT NULL;
UPDATE categories a INNER JOIN categories b ON a.parent_id = b.id SET a.parent_id = NULL;
DELETE FROM categories WHERE parent_id = 0;
