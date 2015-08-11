ALTER TABLE `@prefix@api_tab_messages` DROP FOREIGN KEY fk_user;

ALTER TABLE `@prefix@api_tab_messages` DROP FOREIGN KEY fk_rang;

ALTER TABLE `@prefix@api_tab_messages` ADD `profile` tinyint(2) NOT NULL AFTER `message`;

ALTER TABLE `@prefix@api_tab_messages` ADD `code_user_creation` varchar(100) NOT NULL AFTER `date_expiration`;

UPDATE `@prefix@api_tab_messages` a
  JOIN `@prefix@api_tab_rangs` b ON 1=1 AND a.`id_rang` = b.`id`
SET a.`profile` = b.`indice`
WHERE 1=1
;

UPDATE `@prefix@api_tab_messages` a
  JOIN `@prefix@api_tab_utilisateurs` b ON 1=1 AND a.`id_user` = b.`id`
SET a.`code_user_creation` = b.`code_user`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_messages` DROP `id_user`;

ALTER TABLE `@prefix@api_tab_messages` DROP `id_rang`;

-- --------------------------------------------------------
ALTER TABLE `@prefix@api_tab_messages_lus` DROP FOREIGN KEY fk_user;

ALTER TABLE `@prefix@api_tab_messages_lus` ADD `code_user` varchar(100) NOT NULL AFTER `id`;

UPDATE `@prefix@api_tab_messages` a
  JOIN `@prefix@api_tab_utilisateurs` b ON 1=1 AND a.`id_user` = b.`id`
SET a.`code_user_creation` = b.`code_user`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_messages_lus` DROP `id_user`;
