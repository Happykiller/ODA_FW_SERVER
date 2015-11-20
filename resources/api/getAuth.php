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
// vendor/happykiller/oda/resources/phpsql/getAuth.php?milis=123450&login=VIS&mdp=VIS

//--------------------------------------------------------------------------
if(OdaLib::startsWith($ODA_INTERFACE->inputs["mdp"],"authByGoogle-")){
    $mail = str_replace("authByGoogle-", "", $ODA_INTERFACE->inputs["mdp"]);
    $params = new SimpleObject\OdaPrepareReqSql();
    $params->sql = "select a.`code_user`, a.`password`
        from `api_tab_utilisateurs` a
        where 1=1 
        and a.`code_user` = :login
        and a.`mail` = :mail
    ;";
    $params->bindsValue = [
        "login" => $ODA_INTERFACE->inputs["login"]
        , "mail" => $mail
    ];
    $params->typeSQL = OdaLibBd::SQL_GET_ONE;
    $retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);
    $ODA_INTERFACE->inputs["mdp"] = $retour->data->password;
}

//--------------------------------------------------------------------------
$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "select a.`id_rang`, a.`code_user`
    from `api_tab_utilisateurs` a
    where 1=1 
    and a.`code_user` = :code_user
    and a.`password` = :mdp
;";
$params->bindsValue = [
    "code_user" => $ODA_INTERFACE->inputs["login"]
    , "mdp" => $ODA_INTERFACE->inputs["mdp"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ONE;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//--------------------------------------------------------------------------
//get key
$key = $ODA_INTERFACE->buildSession(array('code_user' => $ODA_INTERFACE->inputs["login"], 'password' => $ODA_INTERFACE->inputs["mdp"]));

$retour->data->keyAuthODA = $key;

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);
