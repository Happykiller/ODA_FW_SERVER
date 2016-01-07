<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("type","msg");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/insertLog.php?milis=123450&type=0&msg=wtf

//--------------------------------------------------------------------------
$id = $ODA_INTERFACE->BD_ENGINE->logTrace($ODA_INTERFACE->inputs["type"], addslashes($ODA_INTERFACE->inputs["msg"]));

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultatInsert";
$params->value = $id;
$ODA_INTERFACE->addDataStr($params);