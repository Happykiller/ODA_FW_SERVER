CREATE TABLE IF NOT EXISTS `@prefix@api_tab_rang_api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interface` varchar(255) NOT NULL,
  `id_rang` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE INDEX api_tab_rang_api_index_rang ON `@prefix@api_tab_rang_api` (`id_rang`);

ALTER TABLE  `@prefix@api_tab_rang_api` ADD CONSTRAINT api_tab_rang_api_fk_rang FOREIGN KEY (  `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;