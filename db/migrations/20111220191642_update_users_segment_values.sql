UPDATE `users` u, `sites` s
SET u.`segment` = s.`segment`
WHERE u.`id` = s.`user_id`; 
