SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------
INSERT INTO `@prefix@api_tab_menu` (`Description`, `Description_courte`, `id_categorie`, `Lien`) VALUES ('oda-tests.title', 'oda-tests.title', 2, 'tests');

UPDATE `@prefix@api_tab_menu_rangs_droit` a
INNER JOIN `@prefix@api_tab_menu` b
ON b.`Lien` = 'tests'
INNER JOIN `@prefix@api_tab_rangs` c
ON c.`id` = a.`id_rang`
AND c.`indice` in (1)
SET `id_menu` = concat(`id_menu`,b.`id`,';');
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=1;