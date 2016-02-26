SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------
INSERT INTO `@prefix@api_tab_parametres`(`param_name`, `param_type`, `param_value`) VALUES ('install_date','varchar', DATE_FORMAT(NOW(),'%Y-%m-%d'));
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS=1;