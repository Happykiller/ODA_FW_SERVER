<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("login", "mdp");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getAuth.php?milis=123450&login=VIS&mdp=VIS

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "select a.`id_rang`, a.`code_user`, a.`password`, a.`mail`
    from `api_tab_utilisateurs` a
    where 1=1
    and a.`code_user` = :code_user
;";
$params->bindsValue = [
    "code_user" => $ODA_INTERFACE->inputs["login"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);
if(!$retour->data){
    $ODA_INTERFACE->dieInError('Auth impossible.(user unknown)');
}else{
    if(OdaLib::startsWith($ODA_INTERFACE->inputs["mdp"],"authByGoogle-")){
        $mail = str_replace("authByGoogle-", "", $ODA_INTERFACE->inputs["mdp"]);
        if($mail !== $retour->data->mail){
            $ODA_INTERFACE->dieInError('Auth impossible.(mail incorrect)');
        }
    }
}

$key = $ODA_INTERFACE->buildSession(array('code_user' => $ODA_INTERFACE->inputs["login"], 'password' => $ODA_INTERFACE->inputs["mdp"], 'dbPassword' => $retour->data->password));

$data = new stdClass();
$data->id_rang = $retour->data->id_rang;
$data->code_user = $retour->data->code_user;
$data->keyAuthODA = $key;
$retour->data = $data;

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);