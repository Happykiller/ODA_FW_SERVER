<?php
namespace Oda\InterfaceRest;

use Exception,
    stdClass, 
    Oda\OdaLibBd,
    Oda\OdaRestInterface,
    Oda\SimpleObject\OdaPrepareInterface, 
    Oda\SimpleObject\OdaPrepareReqSql
;

/**
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.17050200
 */
class RankInterface extends OdaRestInterface {
    /**
     */
     function getAll(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`id`, a.`labelle` as 'label', a.`indice`
                FROM `api_tab_rangs` a
                WHERE 1=1
                ORDER BY a.`indice` desc
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            
            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
     }
}