SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------
ALTER TABLE `@prefix@api_tab_rang_api` ADD `methode` VARCHAR(255) NOT NULL AFTER `interface`;
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=1;