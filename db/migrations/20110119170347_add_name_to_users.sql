ALTER TABLE `users`
    ADD COLUMN `name` varchar(255) AFTER `site_id`;
    
DROP INDEX `by_username_and_password` ON `users`;
CREATE UNIQUE INDEX `by_email_and_password` ON `users`(`email`, `password`);