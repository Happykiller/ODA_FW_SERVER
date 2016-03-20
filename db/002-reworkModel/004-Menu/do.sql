UPDATE `@prefix@api_tab_menu_rangs_droit` a
  JOIN `@prefix@api_tab_rangs` b ON 1=1 AND a.`id_rang` = b.`indice`
SET a.`id_rang` = b.`id`
WHERE 1=1
;

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_menu_rangs_droit` ADD CONSTRAINT fk_rang FOREIGN KEY ( `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;