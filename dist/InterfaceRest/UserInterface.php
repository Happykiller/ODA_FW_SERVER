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
    function getAll(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`code_user`, a.`mail`, a.`nom`, a.`prenom`, b.`labelle`, b.`indice` as 'rankIndice', a.`description`, a.`actif`
                FROM `api_tab_utilisateurs` a, `api_tab_rangs` b
                WHERE 1=1
                AND a.`id_rang` = b.`id`
                ORDER BY a.`actif`, a.`code_user`
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function getAllMail(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`code_user`, a.`mail`
                FROM `api_tab_utilisateurs` a
                WHERE 1=1
                AND a.`actif` = 1
                ORDER BY a.`code_user`
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function getByMail(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT `code_user`
                FROM `api_tab_utilisateurs` a
                WHERE 1=1
                AND a.`mail` = :email
            ;";
            $params->bindsValue = [
                "email" => $this->inputs["email"]
            ];
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function create(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT count(*) as result
                from `api_tab_utilisateurs`
                where 1=1
                AND code_user like '".$this->inputs["userCode"]."%'
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ONE;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            $nbSamePseudo = intval($retour->data->result);

            //--------------------------------------------------------------------------
            if($nbSamePseudo == 0){
                $userCode = $this->inputs["userCode"];
            }else{
                $userCode = $this->inputs["userCode"].$nbSamePseudo;
            }

            $params = new OdaPrepareReqSql();
            $params->sql = "INSERT INTO `api_tab_utilisateurs` 
                (`password`,`code_user`,`nom`,`prenom`,`id_rang`,`montrer_aide_ihm`,`mail`,`actif`,`date_creation`)
                VALUES  
                ( :password, :userCode, :lastName, :firstName, 5, 1, :mail, 1, now())
            ;";
            $params->bindsValue = [
                "firstName" => $this->inputs["firstName"],
                "lastName" => $this->inputs["lastName"],
                "mail" => $this->inputs["mail"],
                "password" => $this->inputs["password"],
                "userCode" => $userCode
            ];
            $params->typeSQL = OdaLibBd::SQL_INSERT_ONE;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->id = $retour->data;
            $params->userCode = $userCode;
            $params->mail = $this->inputs["mail"];
            $this->addDataObject($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
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
            $this->addDataObject($retour->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
     }
    /**
      */
    function updateUser($userCode) {
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "UPDATE `api_tab_utilisateurs`
                SET
                    `id_rang`= :rankId,
                    `mail`= :mail,
                    `actif`= :active,
                    `description`= :desc
                WHERE 1=1
                  AND `code_user` = :userCode
            ;";
            $params->bindsValue = [
                "userCode" => $userCode,
                "mail" => $this->inputs["mail"],
                "active" => $this->inputs["active"],
                "rankId" => $this->inputs["rankId"],
                "desc" => $this->inputs["desc"]
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
    function getActivity(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT IF(b.`code_user`='','N.A',b.`code_user`) as 'code_user', count(*) 'nombre'
                FROM `api_tab_statistiques_site` a, `api_tab_utilisateurs` b, `api_tab_rangs` c
                WHERE 1=1
                AND a.`id_user` = b.`id`
                AND b.`id_rang` = c.`id`
                AND c.`indice` > 1
                GROUP BY a.`id_user`
                ORDER BY `nombre` desc
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
      */
    function updateField() {
        try {
            $params = new stdClass();
            $params->nameObj = "api_tab_utilisateurs";
            $params->keyObj = ["code_user" => $this->user->codeUser];
            $retour = $this->BD_ENGINE->getSingleObject($params);

            if(!password_verify($this->inputs["password"], $retour->password)){
                $this->dieInError('Wrong password.');
            }else{
                $params = new stdClass();
                $params->nameObj = "api_tab_utilisateurs";
                $params->keyObj = ["code_user" => $this->user->codeUser];
                $params->setObj = [$this->inputs["field"] => $this->inputs["value"]];
                $retour = $this->BD_ENGINE->setSingleObj($params);
                $this->addDataStr($retour);
            }
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
}