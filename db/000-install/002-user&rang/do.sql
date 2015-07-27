-- --------------------------------------------------------
--
-- Structure de la table `tab_rangs`
--
CREATE TABLE IF NOT EXISTS `@prefix@api_tab_rangs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `labelle` varchar(250) NOT NULL,
  `indice` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Datas
--
INSERT INTO `@prefix@api_tab_rangs` (`id`, `labelle`, `indice`) VALUES
  (1, 'Administrateur', 1),
  (2, 'Superviseur', 10),
  (3, 'Responsable', 20),
  (4, 'Utilisateur', 30),
  (5, 'Visiteur', 99)
;

--
-- Reserve
--
ALTER TABLE `@prefix@api_tab_rangs` AUTO_INCREMENT = 20;

-- --------------------------------------------------------
--
-- Structure de la table `tab_utilisateurs`
--
CREATE TABLE IF NOT EXISTS `@prefix@api_tab_utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_user` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `id_rang` int(4) NOT NULL,
  `description` varchar(250) NOT NULL,
  `montrer_aide_ihm` int(2) NOT NULL DEFAULT '1',
  `mail` varchar(100) NOT NULL,
  `actif` int(2) NOT NULL DEFAULT '1',
  `date_creation` datetime NOT NULL,
  `date_modif` datetime NOT NULL,
  `theme` varchar(50) NOT NULL,
  `langgue` varchar(50) NOT NULL DEFAULT 'fr',
  PRIMARY KEY (`id`),
  UNIQUE `unique_code_user` (  `code_user` )
);

--
-- Datas
--
INSERT INTO `@prefix@api_tab_utilisateurs` (`code_user`, `password`, `nom`, `prenom`, `id_rang`, `mail`) VALUES
  ('ADMI', 'pass', 'Administrateur', 'Administrateur', 1, 'admin@mail.com'),
  ('VIS', 'VIS', 'Visiteur', 'Visiteur', 5, 'vis@mail.com');

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_utilisateurs` ADD CONSTRAINT fk_rang FOREIGN KEY (  `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;