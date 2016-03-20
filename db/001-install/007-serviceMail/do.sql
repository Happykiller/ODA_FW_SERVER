-- --------------------------------------------------------
--
-- Structure de la table `tab_service_mail_dest`
--
CREATE TABLE `@prefix@api_tab_service_mail` (
  id int(11) NOT NULL AUTO_INCREMENT,
  libelle varchar(100) NOT NULL,
  PRIMARY KEY (id)
);

-- --------------------------------------------------------
--
-- Structure de la table `tab_service_mail_dest`
--
CREATE TABLE `@prefix@api_tab_service_mail_dest` (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_type_mail int(11) NOT NULL,
  id_user varchar(50) NOT NULL,
  nivo varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
);

-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_service_mail_dest` ADD CONSTRAINT fk_type_mail FOREIGN KEY (  `id_type_mail` ) REFERENCES  `@prefix@api_tab_service_mail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;

ALTER TABLE  `@prefix@api_tab_service_mail_dest` ADD CONSTRAINT fk_user FOREIGN KEY (  `id` ) REFERENCES  `@prefix@api_tab_utilisateurs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ;
