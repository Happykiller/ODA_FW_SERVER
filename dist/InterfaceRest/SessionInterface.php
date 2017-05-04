<?php
namespace Oda\InterfaceRest;

use Exception;
use Oda\OdaLib;
use Oda\OdaLibBd;
use Oda\OdaRestInterface;
use Oda\SimpleObject\OdaPrepareReqSql;
use stdClass;

/**
 * SessionInterface
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.1702280
 */
class SessionInterface extends OdaRestInterface {
    /**
     */
    function create(){
        try {
            //--------------------------------------------------------------------------
            $params = new OdaPrepareReqSql();
            $params->sql = "select a.`id_rang`, a.`code_user`, a.`password`, a.`mail`, a.`actif`
                from `api_tab_utilisateurs` a
                where 1=1
                and a.`code_user` = :code_user
            ;";
            $params->bindsValue = [
                "code_user" => $this->inputs["userCode"]
            ];
            $params->typeSQL = OdaLibBd::SQL_GET_ONE;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            if(!$retour->data){
                $this->dieInError('Auth impossible.(user unknown)', $this::STATE_ERROR_AUTH);
            }elseif($retour->data->actif == "0"){
                $this->dieInError('User disabled.', $this::STATE_ERROR_AUTH);
            }else{
                if(OdaLib::startsWith($this->inputs["password"],"authByGoogle-")){
                    $mail = str_replace("authByGoogle-", "", $this->inputs["mdp"]);
                    if($mail !== $retour->data->mail){
                        $this->dieInError('Auth impossible.(mail incorrect)',$this::STATE_ERROR_AUTH);
                    }
                }
            }

            $key = $this->buildSession(array(
                'code_user' => $this->inputs["userCode"], 
                'password' => $this->inputs["password"], 
                'sessionTimeOutMinute' => $this->inputs["sessionTimeOutMinute"], 
                'dbPassword' => $retour->data->password)
            );

            $data = new stdClass();
            $data->id_rang = $retour->data->id_rang;
            $data->code_user = $retour->data->code_user;
            $data->keyAuthODA = $key;
            $retour->data = $data;

            //--------------------------------------------------------------------------
            $params = new stdClass();
            $params->retourSql = $retour;
            $this->addDataReqSQL($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
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