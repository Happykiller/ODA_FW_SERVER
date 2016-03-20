ALTER TABLE `@prefix@api_tab_statistiques_site` DROP FOREIGN KEY fk_user;

ALTER TABLE `@prefix@api_tab_statistiques_site` ADD `code_user` varchar(100) NOT NULL AFTER `date`;

UPDATE `@prefix@api_tab_statistiques_site` a
  JOIN `@prefix@api_tab_utilisateurs` b ON 1=1 AND a.`id_user` = b.`id`
SET a.`code_user` = b.`code_user`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_statistiques_site` DROP `id_user`;