<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;
//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("key");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/phpsql/deleteSession.php?key=e6fff655cb3121c08a8219497ba9358e

//--------------------------------------------------------------------------
$retour = $ODA_INTERFACE->deleteSession($ODA_INTERFACE->inputs["key"]);

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->value = $retour;
$ODA_INTERFACE->addDataStr($params);