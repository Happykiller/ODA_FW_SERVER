<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("code_user");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getMessagesToDisplay.php?milis=123450&ctrl=ok&code_user=VIS

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "SELECT a.`id`
    FROM  `api_tab_utilisateurs` a
    WHERE 1=1
    AND a.`code_user` = :code_user
;";
$params->bindsValue = [
    "code_user" => $ODA_INTERFACE->inputs["code_user"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);
$id_user = $retour->data->id;
    
//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "Select * 
    FROM `api_tab_messages` a, `api_tab_rangs` e
    WHERE 1=1
    AND a.`id_rang` = e.`id`
    AND NOT EXISTS (
        SELECT 1
        FROM  `api_tab_messages_lus` b
        WHERE 1=1
        AND a.`id` = b.`id_message`
        AND b.`id_user` = :id_user
    )
    AND e.`indice` >= (
        SELECT d.`indice`
        FROM `api_tab_utilisateurs` c, `api_tab_rangs` d
        WHERE 1=1
        AND c.`id_rang` = d.`id`
        AND c.`id` = :id_user
    )
    AND IF(a.`date_expiration` != '0000-00-00', a.`date_expiration` > NOW(), (a.`date_creation` + INTERVAL 7 DAY) > NOW())
    ORDER BY a.`id` desc
    LIMIT 0, 10
;";
$params->bindsValue = [
    "id_user" => $id_user
];
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "messages";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);