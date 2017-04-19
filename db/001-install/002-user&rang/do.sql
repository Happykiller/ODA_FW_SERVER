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
  (1, 'oda-rank.admin', 1),
  (2, 'oda-rank.supervisor', 10),
  (3, 'oda-rank.responsible', 20),
  (4, 'oda-rank.user', 30),
  (5, 'oda-rank.visitor', 99)
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
  `password` varchar(60) NOT NULL,
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
  `langue` varchar(50) NOT NULL DEFAULT 'fr',
  PRIMARY KEY (`id`),
  UNIQUE `unique_code_user` (  `code_user` )
);

--
-- Datas
-- ADMI : pass
-- VIS : VIS
--
INSERT INTO `@prefix@api_tab_utilisateurs` (`code_user`, `password`, `nom`, `prenom`, `id_rang`, `mail`) VALUES
  ('ADMI', '$2y$10$co5O0nZScrI0GJ/HnGD.q.M7dGBtDxGeQHqewXJ9GvO8w.K5ot9mi', 'Administrateur', 'Administrateur', 1, 'admin@mail.com'),
  ('VIS', '$2y$10$Y.WdJ4dihlbb/ENMOo6MnuFHqQJ2lJ.fZ2kX1jhlhhzx4XtAMBTzm', 'Visiteur', 'Visiteur', 5, 'vis@mail.com');

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_utilisateurs` ADD CONSTRAINT fk_rang FOREIGN KEY (  `id_rang` ) REFERENCES  `@prefix@api_tab_rangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;