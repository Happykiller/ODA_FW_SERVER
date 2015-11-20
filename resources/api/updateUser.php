<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("mail","actif", "rang", "code_user");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/updateUser.php?milis=123450&code_user=VIS&mail=vis.vis@gmail.com&actif=1&rang=10

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "SELECT a.`id`
    FROM  `api_tab_rangs` a
    WHERE 1=1
    AND a.`indice` = :rang
;";
$params->bindsValue = [
    "rang" => $ODA_INTERFACE->inputs["rang"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);
$id_rang = $retour->data->id;

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "UPDATE `api_tab_utilisateurs`
    SET `mail` = :mail,
    `actif` = :actif,
    `id_rang` = :rang,
    `date_modif` = NOW()
    WHERE 1=1
    AND `code_user` = :code_user
;";
$params->typeSQL = OdaLibBd::SQL_SCRIPT;
$params->bindsValue = [
    "code_user" => $ODA_INTERFACE->inputs["code_user"],
    "mail" => $ODA_INTERFACE->inputs["mail"],
    "actif" => $ODA_INTERFACE->inputs["actif"],
    "rang" => $id_rang
];
$params->typeSQL = OdaLibBd::SQL_SCRIPT;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultatUpdate";
$params->value = $retour->nombre;
$ODA_INTERFACE->addDataStr($params);