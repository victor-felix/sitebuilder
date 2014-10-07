ALTER TABLE  `sites` CHANGE  `android_app_url`  `android_app_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
CHANGE  `ios_app_url`  `ios_app_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
