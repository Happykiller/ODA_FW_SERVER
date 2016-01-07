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
$params->arrayInput = array("sql");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getSQL.php?milis=123456789&sql=SELECT param_name FROM api_tab_parametres

//--------------------------------------------------------------------------
if (preg_match("/\bDELETE\b/i", $ODA_INTERFACE->inputs["sql"])) {
    die('Non autorisé');
}

if (preg_match("/\bDROP\b/i", $ODA_INTERFACE->inputs["sql"])) {
    die('Non autorisé');
}

if (preg_match("/\bUPDATE\b/i", $ODA_INTERFACE->inputs["sql"])) {
    die('Non autorisé');
}

$strSql = stripslashes($ODA_INTERFACE->inputs["sql"]);

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = $strSql;
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);