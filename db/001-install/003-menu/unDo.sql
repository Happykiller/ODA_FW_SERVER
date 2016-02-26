ALTER TABLE `@prefix@api_tab_menu` DROP FOREIGN KEY fk_categorie;

ALTER TABLE `@prefix@api_tab_menu_rangs_droit` DROP FOREIGN KEY fk_rang;

DROP TABLE `@prefix@api_tab_menu`;

DROP TABLE `@prefix@api_tab_menu_categorie`;

DROP TABLE `@prefix@api_tab_menu_rangs_droit`;