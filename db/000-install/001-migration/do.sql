CREATE TABLE IF NOT EXISTS `@prefix@api_tab_migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `dateMigration` datetime NOT NULL,
  PRIMARY KEY (`id`)
)