ALTER TABLE `@prefix@api_tab_menu_rangs_droit` DROP FOREIGN KEY fk_rang;

UPDATE `@prefix@api_tab_menu_rangs_droit` a
  JOIN `@prefix@api_tab_rangs` b ON 1=1 AND a.`id_rang` = b.`id`
SET a.`id_rang` = b.`indice`
WHERE 1=1
;

ALTER TABLE `@prefix@api_tab_menu_rangs_droit` DROP `id_rang`;