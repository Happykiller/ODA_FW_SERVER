<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("mail","actif", "rang", "code_user", "desc");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "UPDATE `api_tab_utilisateurs`
    SET `mail` = :mail,
    `actif` = :actif,
    `id_rang` = :rang,
    `description` = :desc,
    `date_modif` = NOW()
    WHERE 1=1
    AND `code_user` = :code_user
;";
$params->typeSQL = OdaLibBd::SQL_SCRIPT;
$params->bindsValue = [
    "code_user" => $ODA_INTERFACE->inputs["code_user"],
    "mail" => $ODA_INTERFACE->inputs["mail"],
    "actif" => $ODA_INTERFACE->inputs["actif"],
    "rang" => $ODA_INTERFACE->inputs["rang"],
    "desc" => $ODA_INTERFACE->inputs["desc"],
];
$params->typeSQL = OdaLibBd::SQL_SCRIPT;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultatUpdate";
$params->value = $retour->nombre;
$ODA_INTERFACE->addDataStr($params);