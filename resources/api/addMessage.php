<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("message","niveau","profile","date_expiration","code_user");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/addMessage.php?milis=123450&ctrl=ok&message=Hello&niveau=ALERT&profile=30&date_expiration=2015-08-20&code_user=VIS

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
$params->sql = "SELECT a.`id`
    FROM  `api_tab_rangs` a
    WHERE 1=1
    AND a.`indice` = :rang
;";
$params->bindsValue = [
    "rang" => $ODA_INTERFACE->inputs["profile"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);
$id_rang = $retour->data->id;

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "INSERT INTO `api_tab_messages`
    (`actif`, `message`, `id_rang`, `niveau`, `date_expiration`, `id_user`, `date_creation`)
    VALUES 
    ( 1 ,  :message, :id_rang, :niveau, :date_expiration, :id_user, NOW() )
;";
$params->bindsValue = [
    "message" => $ODA_INTERFACE->inputs["message"],
    "id_rang" => $id_rang,
    "niveau" => $ODA_INTERFACE->inputs["niveau"],
    "date_expiration" => $ODA_INTERFACE->inputs["date_expiration"],
    "id_user" => $id_user
];
$params->typeSQL = OdaLibBd::SQL_INSERT_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);  

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultatInsert";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);

//---------------------------------------------------------------------------
