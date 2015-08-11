ALTER TABLE `@prefix@api_tab_statistiques_site` ADD `id_user` INT(10) NOT NULL AFTER `date`;

UPDATE `@prefix@api_tab_statistiques_site` a
  JOIN `@prefix@api_tab_utilisateurs` b ON 1=1 AND a.`code_user` = b.`code_user`
SET a.`id_user` = b.`id`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_statistiques_site` DROP `code_user`;

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_statistiques_site` ADD CONSTRAINT fk_user FOREIGN KEY ( `id_user` ) REFERENCES  `@prefix@api_tab_utilisateurs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;