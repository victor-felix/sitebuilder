update sites set website=CONCAT('http://',website) where website NOT LIKE 'http%' AND website IS NOT NULL AND website != '';

update sites set twitter=CONCAT('http://',twitter) where twitter NOT LIKE 'http%' AND twitter IS NOT NULL AND twitter != '';

update sites set facebook=CONCAT('http://',facebook) where facebook NOT LIKE 'http%' AND facebook IS NOT NULL AND facebook != '';
