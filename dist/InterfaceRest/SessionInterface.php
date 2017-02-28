<?php
namespace Oda\InterfaceRest;

use Exception;
use Oda\OdaLibBd;
use Oda\OdaRestInterface;
use Oda\SimpleObject\OdaPrepareReqSql;
use \stdClass;

/**
 * SessionInterface
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.1702280
 */
class SessionInterface extends OdaRestInterface {
    /**
     */
    function getBykey($key) {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`id`, a.`datas`, a.`dateCreation`, a.`periodeValideMinute`
                FROM `api_tab_session` a
                WHERE 1=1
                AND a.`key` = :key
            ;";
            $params->bindsValue = [
                "key" => $key
            ];
            $params->typeSQL = OdaLibBd::SQL_GET_ONE;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataObject($retour->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
}