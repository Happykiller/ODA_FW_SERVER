-- --------------------------------------------------------
--
-- Structure de la table `api_tab_messages`
--

CREATE TABLE IF NOT EXISTS `@prefix@api_tab_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actif` tinyint(2) NOT NULL,
  `message` varchar(500) NOT NULL,
  `id_rang` int(10) NOT NULL,
  `niveau` varchar(100) NOT NULL,
  `date_expiration` date NOT NULL,
  `id_user` int(10) NOT NULL,
  `date_creation` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Structure de la table `api_tab_messages_lus`
--

CREATE TABLE IF NOT EXISTS `@prefix@api_tab_messages_lus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `id_message` int(11) NOT NULL,
  `datelu` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_messages` ADD CONSTRAINT fk_user FOREIGN KEY ( `id_user` ) REFERENCES  `@prefix@api_tab_utilisateurs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;

ALTER TABLE  `@prefix@api_tab_messages` ADD CONSTRAINT fk_rang FOREIGN KEY ( `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;

ALTER TABLE  `@prefix@api_tab_messages_lus` ADD CONSTRAINT fk_user FOREIGN KEY ( `id_user` ) REFERENCES  `@prefix@api_tab_utilisateurs` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;