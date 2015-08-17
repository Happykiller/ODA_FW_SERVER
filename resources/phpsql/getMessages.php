<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array();
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/phpsql/getMessages.php?milis=123450&ctrl=ok
    
//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "Select a.`actif`, a.`message`, b.`labelle` as 'profile', a.`niveau`, a.`date_expiration`, c.`code_user`, a.`date_creation`
    FROM `api_tab_messages` a, `api_tab_rangs` b, `api_tab_utilisateurs` c
    WHERE 1=1
    AND a.`id_rang` = b.`id`
    AND a.`id_user` = c.`id`
    ORDER BY a.`id` desc
    LIMIT 0, 10
;";
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "messages";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);