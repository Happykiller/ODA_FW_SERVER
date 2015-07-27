-- --------------------------------------------------------
--
-- Structure de la table `tab_parametres`
--
CREATE TABLE IF NOT EXISTS `@prefix@tab_parametres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param_name` varchar(50) NOT NULL,
  `param_type` varchar(100) NOT NULL,
  `param_value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Réserve un plage pour le système
--
ALTER TABLE `@prefix@tab_parametres` AUTO_INCREMENT = 100;

--
-- Datas
--
INSERT INTO `@prefix@tab_parametres` (`id`, `param_name`, `param_type`, `param_value`) VALUES
  (1, 'nom_site', 'varchar', 'siteName'),
  (2, 'maintenance', 'int', '0'),
  (5, 'contact_mail_administrateur', 'int', 'admin@mail.com'),
  (6, 'theme_defaut', 'varchar', '');

-- --------------------------------------------------------
--
-- Structure de la table `tab_log`
--
CREATE TABLE IF NOT EXISTS `@prefix@tab_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dateTime` datetime NOT NULL,
  `id_type` int(4) NOT NULL,
  `commentaires` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
--
-- Structure de la table `api_tab_log_type`
--
CREATE TABLE IF NOT EXISTS `@prefix@api_tab_log_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@tab_log` ADD CONSTRAINT fk_type FOREIGN KEY (  `id_type` ) REFERENCES  `@prefix@api_tab_log_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
