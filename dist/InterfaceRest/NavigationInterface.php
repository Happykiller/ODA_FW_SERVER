<?php
namespace Oda\InterfaceRest;

use Exception;
use Oda\OdaLibBd;
use Oda\OdaRestInterface;
use Oda\SimpleObject\OdaPrepareReqSql;
use \stdClass;

/**
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.170421
 */
class NavigationInterface extends OdaRestInterface {
    /**
     */
    function getAllPage() {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`id`, a.`Description_courte` as 'label', a.`Description` as 'description', a.`id_categorie` as 'category_id', b.`Description` as 'category_desc', a.`Lien` as 'link'
                FROM `api_tab_menu` a, `api_tab_menu_categorie` b
                WHERE 1=1
                AND a.`id_categorie` = b.`id`
                ORDER BY a.`id` ASC
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataObject($retour->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function getAllRank() {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`id`, a.`labelle` as 'label', a.`indice` as 'index'
                FROM `api_tab_rangs` a
                WHERE 1=1
                ORDER BY a.`indice` ASC
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataObject($retour->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function getRights() {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT `id` , `id_rang` as 'rank_id', `id_menu` as `menu_ids`
                FROM `api_tab_menu_rangs_droit` a
                WHERE 1=1
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataObject($retour->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function updateRight($id) {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "UPDATE `api_tab_menu_rangs_droit`
                SET
                    `id_menu`= :value
                WHERE 1=1
                  AND `id` = :id
            ;";
            $params->bindsValue = [
                "value" => $this->inputs["value"],
                "id" => $id
            ];
            $params->typeSQL = OdaLibBd::SQL_SCRIPT;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->value = $retour->data;
            $this->addDataStr($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
}