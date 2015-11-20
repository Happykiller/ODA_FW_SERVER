<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("param_name");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/phpsql/getParam.php?milis=123450&param_name=nom_site

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->nameObj = "api_tab_parametres";
$params->keyObj = ["param_name" => $ODA_INTERFACE->inputs["param_name"]];
$retour = $ODA_INTERFACE->BD_ENGINE->getSingleObject($params);

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "leParametre";
$params->value = $retour;
$ODA_INTERFACE->addDataObject($params);