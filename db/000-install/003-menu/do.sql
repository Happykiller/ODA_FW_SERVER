-- --------------------------------------------------------
--
-- Structure de la table `tab_menu_categorie`
-- Reserve 1-9 API
-- Reserve 10-19 API_RH
-- Reserve 70-79 Projet
-- Reserve 99 Liens externes
--
CREATE TABLE IF NOT EXISTS `@prefix@tab_menu_categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Datas
--
INSERT INTO `@prefix@tab_menu_categorie` (`id`, `Description`) VALUES
  (1, 'L''accueil'),
  (2, 'Administration'),
  (3, 'Gestion'),
  (4, 'Rapports'),
  (99, 'Liens externs');

--
-- Reserve
--
ALTER TABLE `@prefix@tab_menu_categorie` AUTO_INCREMENT = 70;

-- --------------------------------------------------------
--
-- Structure de la table `tab_menu`
-- Réserve un plage de tag pour le système
-- Réservé 1-19 API
-- Réservé 20-29 API_RH
-- Réservé : 70-89 Projet
-- Réservé : 100+ pour le projet
--
CREATE TABLE IF NOT EXISTS `@prefix@tab_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(250) NOT NULL,
  `Description_courte` varchar(50) NOT NULL,
  `id_categorie` int(5) NOT NULL,
  `Lien` text NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Datas
--
INSERT INTO `@prefix@tab_menu` (`id`, `Description`, `Description_courte`, `id_categorie`, `Lien`) VALUES
  (1, 'Statistiques', 'Statistiques', 2, 'stats'),
  (2, 'Administration', 'Administration', 2, 'admin'),
  (3, 'Supervision', 'Supervision', 2, 'supervision')
;

--
-- Reserve
--
ALTER TABLE `@prefix@tab_menu` AUTO_INCREMENT = 100;

-- --------------------------------------------------------
--
-- Structure de la table `tab_menu_rangs_droit`
--
CREATE TABLE IF NOT EXISTS `@prefix@tab_menu_rangs_droit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_rang` int(5) NOT NULL,
  `id_menu` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Datas
--
INSERT INTO `@prefix@tab_menu_rangs_droit` (`id`, `id_rang`, `id_menu`) VALUES
  (1, 1, ';1;2;3;'),
  (2, 2, ';1;2;3;'),
  (3, 3, ';'),
  (4, 4, ';'),
  (5, 5, ';')
;

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@tab_menu` ADD CONSTRAINT fk_categorie FOREIGN KEY (  `id_categorie` ) REFERENCES  `@prefix@tab_menu_categorie` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;

ALTER TABLE  `@prefix@tab_menu_rangs_droit` ADD CONSTRAINT fk_rang FOREIGN KEY (  `id_rang` ) REFERENCES  `@prefix@tab_rangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
