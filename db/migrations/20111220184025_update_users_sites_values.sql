INSERT INTO `users_sites` (`user_id`, `site_id`, `segment`)
	SELECT `user_id`, `id`, `segment` FROM `sites` where `user_id` IS NOT NULL;
