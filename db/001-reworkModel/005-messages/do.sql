ALTER TABLE `@prefix@api_tab_messages` ADD `id_user` INT(10) NOT NULL AFTER `date_expiration`;

ALTER TABLE `@prefix@api_tab_messages` ADD `id_rang` INT(10) NOT NULL AFTER `message`;

UPDATE `@prefix@api_tab_messages` a
  JOIN `@prefix@api_tab_rangs` b ON 1=1 AND a.`profile` = b.`indice`
SET a.`id_rang` = b.`id`
WHERE 1=1
;

UPDATE `@prefix@api_tab_messages` a
  JOIN `@prefix@api_tab_utilisateurs` b ON 1=1 AND a.`code_user_creation` = b.`code_user`
SET a.`id_user` = b.`id`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_messages` DROP `profile`;

ALTER TABLE `@prefix@api_tab_messages` DROP `code_user_creation`;

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_messages` ADD CONSTRAINT fk_user FOREIGN KEY ( `id_user` ) REFERENCES  `@prefix@api_tab_utilisateurs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_messages` ADD CONSTRAINT fk_rang FOREIGN KEY ( `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;