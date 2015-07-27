ALTER TABLE `@prefix@api_tab_utilisateurs` DROP FOREIGN KEY fk_rang;

ALTER TABLE `@prefix@api_tab_utilisateurs` ADD `login` INT(4) NOT NULL AFTER `id`;

UPDATE `@prefix@api_tab_utilisateurs` a
SET a.`login` = b.`code_user`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_utilisateurs` ADD `profile` INT(4) NOT NULL AFTER `prenom`;

UPDATE `@prefix@api_tab_utilisateurs` a
  JOIN `@prefix@api_tab_rangs` b ON 1=1 AND a.`id_rang` = b.`id`
SET a.`profile` = b.`indice`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_utilisateurs` DROP `id_rang`;