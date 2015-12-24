<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("user","page","action");
$params->modeDebug = false;
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/addStat.php?milis=123450&ctrl=ok&user=ADMI&page=page_home.html&action=checkAuth%20:%20ok

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "INSERT INTO `api_tab_statistiques_site`
    (`date`, `id_user`, `page`, `action`)
    SELECT NOW(), `api_tab_utilisateurs`.id, :page, :nature
    FROM `api_tab_utilisateurs`
    WHERE 1=1
    AND `api_tab_utilisateurs`.`code_user` = :user
;";
$params->bindsValue = [
    "user" => $ODA_INTERFACE->inputs["user"],
    "page" => $ODA_INTERFACE->inputs["page"],
    "nature" => $ODA_INTERFACE->inputs["action"]
];
$params->typeSQL = OdaLibBd::SQL_INSERT_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);   

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultatInsert";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);

//---------------------------------------------------------------------------