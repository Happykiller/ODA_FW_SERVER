ALTER TABLE `@prefix@api_tab_contact` DROP FOREIGN KEY fk_user;

ALTER TABLE `@prefix@api_tab_contact` ADD `code_user` VARCHAR(10) NOT NULL AFTER `message`;

UPDATE `@prefix@api_tab_contact` a
  JOIN `@prefix@api_tab_utilisateurs` b ON 1=1 AND a.`id_user` = b.`id`
SET a.`code_user` = b.`code_user`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_contact` DROP `id_user`;