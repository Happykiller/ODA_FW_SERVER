--
-- Datas
--
INSERT INTO `@prefix@api_tab_rang_api` (`interface`, `id_rang`, `open`)
 SELECT 'getListMail',  `id` , FALSE
 FROM `@prefix@api_tab_rangs`
 WHERE 1=1
 AND `indice` = 20
;