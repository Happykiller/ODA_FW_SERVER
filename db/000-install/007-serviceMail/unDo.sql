ALTER TABLE `@prefix@tab_service_mail_dest` DROP FOREIGN KEY fk_type_mail;

ALTER TABLE `@prefix@tab_service_mail_dest` DROP FOREIGN KEY fk_user;

DROP TABLE `@prefix@tab_service_mail_dest`;

DROP TABLE `@prefix@tab_service_mail`;