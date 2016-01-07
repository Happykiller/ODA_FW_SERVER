<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("code_user","mdp","champs","value");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/setChangeProfile.php?milis=123450&code_user=VIS&mdp=VIS&champs=prenom&value=kikoo
    
//--------------------------------------------------------------------------
$params = new \stdClass();
$params->nameObj = "api_tab_utilisateurs";
$params->keyObj = ["code_user" => $ODA_INTERFACE->inputs["code_user"]];
$retour = $ODA_INTERFACE->BD_ENGINE->getSingleObject($params);

if(!password_verify($ODA_INTERFACE->inputs["mdp"], $retour->password)){
    $ODA_INTERFACE->dieInError('Mot de passe éronné.');
}else{
    $params = new \stdClass();
    $params->nameObj = "api_tab_utilisateurs";
    $params->keyObj = ["code_user" => $ODA_INTERFACE->inputs["code_user"]];
    $params->setObj = [$ODA_INTERFACE->inputs["champs"] => $ODA_INTERFACE->inputs["value"]];
    $retour = $ODA_INTERFACE->BD_ENGINE->setSingleObj($params);
    
    $params = new \stdClass();
    $params->label = "resultat";
    $params->value = $retour;
    $ODA_INTERFACE->addDataStr($params);
}