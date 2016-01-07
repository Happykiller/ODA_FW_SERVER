<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("param_name","param_value");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/setParam.php?milis=123456789&param_name=nom_site&param_value=test

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->nameObj = "api_tab_parametres";
$params->keyObj = ["param_name" => $ODA_INTERFACE->inputs["param_name"]];
$params->setObj = ["param_value" => $ODA_INTERFACE->inputs["param_value"]];
$id = $ODA_INTERFACE->BD_ENGINE->setSingleObj($params);

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->value = $id;
$ODA_INTERFACE->addDataStr($params);