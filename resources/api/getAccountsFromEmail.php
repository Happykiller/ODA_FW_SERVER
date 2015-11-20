<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$params->arrayInput = array("email");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getAccountsFromEmail.php?email=test@mail.com

//--------------------------------------------------------------------------
$params = new OdaPrepareReqSql();
$params->sql = "SELECT `code_user`
    FROM `api_tab_utilisateurs` a
    WHERE 1=1
    AND a.`mail` = :email
;";
$params->bindsValue = [
    "email" => $ODA_INTERFACE->inputs["email"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

$params = new \stdClass();
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);