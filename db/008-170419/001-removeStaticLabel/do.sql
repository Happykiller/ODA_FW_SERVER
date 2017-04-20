SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------
UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.home',`Description_courte`='oda-menu.home',`Lien`='home'
WHERE 1=1
AND `id` = 1;

UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.contact',`Description_courte`='oda-menu.contact',`Lien`='contact'
WHERE 1=1
AND `id` = 2;

UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.faq',`Description_courte`='oda-menu.faq',`Lien`='faq'
WHERE 1=1
AND `id` = 3;

UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.stats',`Description_courte`='oda-menu.stats',`Lien`='stats'
WHERE 1=1
AND `id` = 4;

UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.admin',`Description_courte`='oda-menu.admin',`Lien`='admin'
WHERE 1=1
AND `id` = 5;

UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.supervision',`Description_courte`='oda-menu.supervision',`Lien`='supervision'
WHERE 1=1
AND `id` = 6;

UPDATE `@prefix@api_tab_menu` 
SET `Description`='oda-menu.profile',`Description_courte`='oda-menu.profile',`Lien`='profile'
WHERE 1=1
AND `id` = 7;


UPDATE `@prefix@api_tab_menu_categorie` 
SET `Description`='oda-menu-cate.home'
WHERE 1=1
AND `id` = 1;

UPDATE `@prefix@api_tab_menu_categorie` 
SET `Description`='oda-menu-cate.admin'
WHERE 1=1
AND `id` = 2;

UPDATE `@prefix@api_tab_menu_categorie` 
SET `Description`='oda-menu-cate.manage'
WHERE 1=1
AND `id` = 3;

UPDATE `@prefix@api_tab_menu_categorie` 
SET `Description`='oda-menu-cate.reports'
WHERE 1=1
AND `id` = 4;

UPDATE `@prefix@api_tab_menu_categorie` 
SET `Description`='oda-menu-cate.hiddenLink'
WHERE 1=1
AND `id` = 98;

UPDATE `@prefix@api_tab_menu_categorie` 
SET `Description`='oda-menu-cate.extLink'
WHERE 1=1
AND `id` = 99;


UPDATE `@prefix@api_tab_rangs` 
SET `labelle`='oda-rank.admin'
WHERE 1=1
AND `id` = 1;

UPDATE `@prefix@api_tab_rangs` 
SET `labelle`='oda-rank.supervisor'
WHERE 1=1
AND `id` = 2;

UPDATE `@prefix@api_tab_rangs` 
SET `labelle`='oda-rank.responsible'
WHERE 1=1
AND `id` = 3;

UPDATE `@prefix@api_tab_rangs` 
SET `labelle`='oda-rank.user'
WHERE 1=1
AND `id` = 4;

UPDATE `@prefix@api_tab_rangs` 
SET `labelle`='oda-rank.visitor'
WHERE 1=1
AND `id` = 5;
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=1;
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=1;