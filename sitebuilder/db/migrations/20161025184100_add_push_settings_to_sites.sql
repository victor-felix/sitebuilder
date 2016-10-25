ALTER TABLE  `sites`
ADD  `pushnotif_service` VARCHAR(32) NULL,
ADD  `pushnotif_app_auth_token` VARCHAR(255) NULL,
CHANGE `pushwoosh_app_id` `pushnotif_app_id` VARCHAR(255) NULL;

UPDATE `sites` SET `pushnotif_service` = "pushwoosh";
