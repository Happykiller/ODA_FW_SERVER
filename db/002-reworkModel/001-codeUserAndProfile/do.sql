ALTER TABLE `@prefix@api_tab_utilisateurs` DROP `login`;

ALTER TABLE `@prefix@api_tab_utilisateurs` ADD `id_rang` INT(10) NOT NULL AFTER `prenom`;

ALTER TABLE `@prefix@api_tab_utilisateurs` ADD `langue` varchar(50) NOT NULL DEFAULT 'fr' AFTER `theme`;

UPDATE `@prefix@api_tab_utilisateurs` a
  JOIN `@prefix@api_tab_rangs` b ON 1=1 AND a.`profile` = b.`indice`
SET a.`id_rang` = b.`id`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_utilisateurs` DROP `profile`;

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_utilisateurs` ADD CONSTRAINT fk_rang FOREIGN KEY ( `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;