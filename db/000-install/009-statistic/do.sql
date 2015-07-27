-- --------------------------------------------------------
--
-- Structure de la table `tab_statistiques_site`
--
CREATE TABLE IF NOT EXISTS `@prefix@tab_statistiques_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `id_user` varchar(50) NOT NULL,
  `page` varchar(250) NOT NULL,
  `action` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@tab_statistiques_site` ADD CONSTRAINT fk_user FOREIGN KEY (  `id_user` ) REFERENCES  `@prefix@tab_utilisateurs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
