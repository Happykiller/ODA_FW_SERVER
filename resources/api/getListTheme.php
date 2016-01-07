<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("code_user");
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getListTheme.php?milis=123450&code_user=VIS

//--------------------------------------------------------------------------
$theme_defaut = $ODA_INTERFACE->getParameter("theme_defaut");

$params = new \stdClass();
$params->label = "theme";
if(is_null($theme_defaut)){
    $params->value = "notAvailable";
}else{
    $params->value = $theme_defaut;
}
$ODA_INTERFACE->addDataStr($params);

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->nameObj = "api_tab_utilisateurs";
$params->keyObj = ["code_user" => $ODA_INTERFACE->inputs["code_user"]];
$params->debug = false;
$retour = $ODA_INTERFACE->BD_ENGINE->getSingleObject($params);

$params = new \stdClass();
$params->label = "themePerso";
if(!isset($retour->theme)){
    $params->value = "notAvailable";
}else{
    $params->value = $retour->theme;
}
$ODA_INTERFACE->addDataStr($params);

//--------------------------------------------------------------------------
$path = '../../css/themes/';

$liste_theme = array();

$theme = new \stdClass();
$theme -> nom = 'default';
$theme -> path = 'vendor/happykiller/oda/resources/api/css/themes/';
$liste_theme[] = $theme;

$dir = opendir($path); 
while($file = readdir($dir)) {
    if(is_dir($path.$file)){
        if(($file != '.') && ($file != '..')){
            $theme = new \stdClass();
            $theme -> nom = $file;
            $theme -> path = 'css/themes/';
            $liste_theme[] = $theme;
        }
    }
}
closedir($dir);

$params = new \stdClass();
$params->label = "listTheme";
$params->value = $liste_theme;
$ODA_INTERFACE->addDataStr($params);