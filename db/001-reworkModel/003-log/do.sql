-- --------------------------------------------------------
--
-- Contraites
--
ALTER TABLE  `@prefix@api_tab_log` ADD CONSTRAINT fk_type_log FOREIGN KEY ( `type` ) REFERENCES  `@prefix@api_tab_log_type` ( `id` ) ON DELETE NO ACTION ON UPDATE NO ACTION ;