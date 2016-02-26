-- -------------------------------------------------------
--
-- Structure de la table `api_tab_session`
--
CREATE TABLE IF NOT EXISTS `@prefix@api_tab_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `datas` text NOT NULL,
  `dateCreation` datetime NOT NULL,
  `periodeValideMinute` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Datas
--
INSERT INTO `@prefix@api_tab_session` (`id`, `key`, `datas`, `dateCreation`, `periodeValideMinute`) VALUES
  (1, '42c643cc44c593c5c2b4c5f6d40489dd', '{"code_user" : "passepartout"}', '2013-01-01 00:00:01', 0);

-- --------------------------------------------------------
--
-- Structure de la table `api_transaction`
--
CREATE TABLE IF NOT EXISTS `@prefix@api_tab_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `input` text NOT NULL,
  `output` MEDIUMTEXT NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  PRIMARY KEY (`id`)
);