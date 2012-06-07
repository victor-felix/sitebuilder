ALTER TABLE `users_sites`  
ADD `role` INT(1) NOT NULL DEFAULT '1',  
ADD `modified` DATETIME NOT NULL,  
ADD `created` DATETIME NOT NULL;
