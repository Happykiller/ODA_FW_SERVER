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
// vendor/happykiller/oda/resources/phpsql/getAuthInfo.php?milis=123456789&code_user=VIS

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "select a.`id` as 'id_user', a.`code_user`, a.`nom`, a.`prenom`, b.`indice` as 'profile', b.`labelle`, a.`id_rang`, a.`montrer_aide_ihm`, a.`mail`, a.`langue`
    from `api_tab_utilisateurs` a, `api_tab_rangs` b
    where 1=1 
    and a.`id_rang` = b.`id`
    and a.`code_user` = :code_user
;";
$params->bindsValue = [
    "code_user" => $ODA_INTERFACE->inputs["code_user"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);