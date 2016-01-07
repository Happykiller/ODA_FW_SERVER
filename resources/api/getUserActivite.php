<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->modePublic = false;
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getUserActivite.php

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "SELECT IF(b.`code_user`='','N.A',b.`code_user`) as 'code_user', count(*) 'nombre'
    FROM `api_tab_statistiques_site` a, `api_tab_utilisateurs` b, `api_tab_rangs` c
    WHERE 1=1
    AND a.`id_user` = b.`id`
    AND b.`id_rang` = c.`id`
    AND c.`indice` > 1
    GROUP BY a.`id_user`
    ORDER BY `nombre` desc
;";
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);