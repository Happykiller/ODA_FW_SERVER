ALTER TABLE `@prefix@api_tab_rang_api` DROP FOREIGN KEY fk_rang;

DROP TABLE `@prefix@api_tab_rang_api`;

DELETE FROM `@prefix@api_tab_rang_api`
WHERE 1=1
      AND `interface` = 'getListMail'
;