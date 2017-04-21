<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

//--------------------------------------------------------------------------
//Build the interface
$params = new SimpleObject\OdaPrepareInterface();
$params->arrayInput = array("rang");
$params->arrayInputOpt = array("id_page" => 0);
$ODA_INTERFACE = new OdaLibInterface($params);

//--------------------------------------------------------------------------
// vendor/happykiller/oda/resources/api/getMenu.php?milis=123450&rang=30&id_page=1

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

$filterRank = "";
if($ODA_INTERFACE->inputs["rang"] != "1"){
    $filterRank = "AND EXISTS (SELECT 1 FROM `api_tab_menu_rangs_droit` c WHERE c.`id_rang` = ".$id_rang." and c.`id_menu` like CONCAT('%;',a.`id`,';%'))";
}

$params = new SimpleObject\OdaPrepareReqSql();
$params->sql = "SELECT a.`id`, a.`Description` as 'Description_menu', a.`Description_courte`, a.`Lien`, a.`id_categorie`, b.`Description` as 'Description_cate', IF(a.`id` = :id_page, 1, 0) as 'selected'
    FROM  `api_tab_menu` a, `api_tab_menu_categorie` b
    WHERE 1=1
    AND a.`id_categorie` = b.id
    ".$filterRank."
    ORDER BY a.`id_categorie` asc, a.`Description_courte` asc
;";
$params->bindsValue = [
    "id_page" => $ODA_INTERFACE->inputs["id_page"]
];
$params->typeSQL = OdaLibBd::SQL_GET_ALL;
$retour = $ODA_INTERFACE->BD_ENGINE->reqODASQL($params);

//--------------------------------------------------------------------------
$params = new \stdClass();
$params->label = "resultat";
$params->retourSql = $retour;
$ODA_INTERFACE->addDataReqSQL($params);