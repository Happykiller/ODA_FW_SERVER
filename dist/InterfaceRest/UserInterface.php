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
            $this->dieInError($ex.'');
        }
    }
    /**
     */
     function getCurrent(){
        try {
            $this->getByCode($this->user->codeUser);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
     }
    /**
     */
     function getByCode($userCode){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`id` AS 'id_user', a.`code_user`, a.`nom`, 
                a.`prenom`, b.`indice` AS 'profile', b.`labelle`, a.`id_rang`, 
                a.`montrer_aide_ihm`, a.`mail`, a.`langue`, a.`description`, a.`actif`
                FROM `api_tab_utilisateurs` a, `api_tab_rangs` b
                WHERE 1=1 
                AND a.`id_rang` = b.`id`
                AND a.`code_user` = :code_user
            ;";
            $params->bindsValue = [
                "code_user" => $userCode
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