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
class MessageInterface extends OdaRestInterface {
    /**
     */
    function create(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "INSERT INTO `api_tab_messages`
                (`actif`, `message`, `id_rang`, `niveau`, `date_expiration`, `id_user`, `date_creation`)
                VALUES 
                ( 1 ,  :message, :rankId, :level, :expirationDate, :userId, NOW() )
            ;";
            $params->bindsValue = [
                "userId" => $this->inputs["userId"],
                "message" => $this->inputs["message"],
                "level" => $this->inputs["level"],
                "expirationDate" => $this->inputs["expirationDate"],
                "rankId" => $this->inputs["rankId"]
            ];
            $params->typeSQL = OdaLibBd::SQL_INSERT_ONE;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataReqSQL($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function getAll(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`actif`, a.`message`, b.`labelle` AS 'profile', a.`niveau`, a.`date_expiration`, c.`code_user`, a.`date_creation`
                FROM `api_tab_messages` a, `api_tab_rangs` b, `api_tab_utilisateurs` c
                WHERE 1=1
                AND a.`id_rang` = b.`id`
                AND a.`id_user` = c.`id`
                ORDER BY a.`id` DESC
                LIMIT 0, 10
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
    /**
     */
    function getForCurrentUser(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT a.`id`, a.`niveau` AS 'level' , a.`message`
                FROM `api_tab_messages` a, `api_tab_rangs` e
                WHERE 1=1
                AND a.`id_rang` = e.`id`
                AND NOT EXISTS (
                    SELECT 1
                    FROM  `api_tab_messages_lus` b
                    WHERE 1=1
                    AND a.`id` = b.`id_message`
                    AND b.`id_user` = :userId
                )
                AND e.`indice` >= (
                    SELECT d.`indice`
                    FROM `api_tab_utilisateurs` c, `api_tab_rangs` d
                    WHERE 1=1
                    AND c.`id_rang` = d.`id`
                    AND c.`id` = :userId
                )
                AND IF(a.`date_expiration` != '0000-00-00', a.`date_expiration` > NOW(), (a.`date_creation` + INTERVAL 7 DAY) > NOW())
                ORDER BY a.`id` desc
                LIMIT 0, 10
            ;";
            $params->bindsValue = [
                "userId" => $this->user->id
            ];
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            
            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
    /**
     */
    function setReadForCurrentUser($messageId){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "INSERT INTO `api_tab_messages_lus` (`id_user`, `id_message`, `datelu`)
                SELECT :id_user, a.`id` , NOW()
                FROM `api_tab_messages` a, `api_tab_rangs` e
                WHERE 1=1
                AND a.`id_rang` = e.`id`
                AND NOT EXISTS (
                    SELECT 1
                    FROM  `api_tab_messages_lus` b
                    WHERE 1=1
                    AND a.`id` = b.`id_message`
                    AND b.`id_user` = :id_user
                )
                AND e.`indice` >= (
                    SELECT d.`indice`
                    FROM `api_tab_utilisateurs` c, `api_tab_rangs` d
                    WHERE 1=1
                    AND c.`id_rang` = d.`id`
                    AND c.`id` = :id_user
                )
                AND IF(a.`date_expiration` != '0000-00-00', a.`date_expiration` > NOW(), (a.`date_creation` + INTERVAL 7 DAY) > NOW())
                ORDER BY a.`id` desc
                LIMIT 0, 10
            ;";
            $params->bindsValue = [
                "id_user" => $this->user->id
            ];
            $params->typeSQL = OdaLibBd::SQL_INSERT_ONE;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataReqSQL($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
}