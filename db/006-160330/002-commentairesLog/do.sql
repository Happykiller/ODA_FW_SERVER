SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------
ALTER TABLE `@prefix@api_tab_log` CHANGE `commentaires` `commentaires` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=1;