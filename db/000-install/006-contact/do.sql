-- --------------------------------------------------------
--
-- Structure de la table `api_tab_contact`
--
CREATE TABLE IF NOT EXISTS `@prefix@api_tab_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_enreg` datetime NOT NULL,
  `reponse` varchar(250) NOT NULL,
  `message` text NOT NULL,
  `id_user` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_contact` ADD CONSTRAINT fk_user FOREIGN KEY (  `id` ) REFERENCES  `@prefix@tab_utilisateurs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
