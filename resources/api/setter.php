<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("table","set","filtre");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/setter.php?milis=123450&table=api_tab_utilisateurs&set={"champ":"theme","valeur":"essai","type":"PARAM_STR"}&filtre={"champ":"code_user","valeur":"VIS","type":"PARAM_STR"}

//--------------------------------------------------------------------------
$jsonSet = $ODA_INTERFACE->inputs["set"];
$jsonFiltre = $ODA_INTERFACE->inputs["filtre"];

$strSet = "";
$champ = $jsonSet["champ"];
$valeur = $jsonSet["valeur"];
$type = $jsonSet["type"];
if($type == 'PARAM_STR'){
    $strSet .= " `".$champ."` = '".$valeur."' ";
}else{
    $strSet .= " `".$champ."` = ".$valeur." ";
}

$strFiltre = "";
$champ = $jsonFiltre["champ"];
$valeur = $jsonFiltre["valeur"];
$type = $jsonFiltre["type"];
if($type == 'PARAM_STR'){
    $strFiltre .= " AND `".$champ."` = '".$valeur."' ";
}else{
    $strFiltre .= " AND `".$champ."` = ".$valeur." ";
}

$strSql = "UPDATE `".$ODA_INTERFACE->inputs["table"]."` a
    SET ".$strSet."
    WHERE 1=1
    ".$strFiltre."
";

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = $strSql;
$params->typeSQL = OdaLibBd::SQL_SCRIPT;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);


//---------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->value = $retour->nombre;
$ODA_INTERFACE->addDataStr($params);