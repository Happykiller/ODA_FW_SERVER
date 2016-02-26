ALTER TABLE `@prefix@api_tab_utilisateurs` CHANGE `password` `password` VARCHAR(60) NOT NULL;

UPDATE `@prefix@api_tab_utilisateurs`
SET `password` = '$2y$10$lEvXbCi0w23grEdoK9jeT.EjhGAoIFF0MS53i8YHpI183qs505y2q'
WHERE `password` = 'hunter';

UPDATE `@prefix@api_tab_utilisateurs`
SET `password` = '$2y$10$co5O0nZScrI0GJ/HnGD.q.M7dGBtDxGeQHqewXJ9GvO8w.K5ot9mi'
WHERE `password` = 'pass';

UPDATE `@prefix@api_tab_utilisateurs`
SET `password` = '$2y$10$Y.WdJ4dihlbb/ENMOo6MnuFHqQJ2lJ.fZ2kX1jhlhhzx4XtAMBTzm'
WHERE `password` = 'VIS';
