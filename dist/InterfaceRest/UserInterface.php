<?php
namespace Oda\InterfaceRest;

use Exception;
use Oda\OdaLibBd;
use Oda\OdaRestInterface;
use Oda\SimpleObject\OdaPrepareReqSql;
use \stdClass;

/**
 * Project class
 *
 * Tool
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.150221
 */
class UserInterface extends OdaRestInterface {
    /**
     */
    function resetPwd() {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "UPDATE `api_tab_utilisateurs`
                SET
                    `password`= :pwd
                WHERE 1=1
                  AND `code_user` = :userCode
                  AND `mail` = :mail
            ;";
            $params->bindsValue = [
                "userCode" => $this->inputs["userCode"],
                "pwd" =>  password_hash($this->inputs["pwd"], PASSWORD_DEFAULT),
                "mail" => $this->inputs["email"]
            ];
            $params->typeSQL = OdaLibBd::SQL_SCRIPT;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->value = $retour->data;
            $this->addDataStr($params);
        } catch (Exception $ex) {
            $this->object_retour->strErreur = $ex.'';
            $this->object_retour->statut = self::STATE_ERROR;
            die();
        }
    }
}