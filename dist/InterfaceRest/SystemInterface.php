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
 * @version 0.17050300
 */
class SystemInterface extends OdaRestInterface {
    /**
     */
     function createPageTrace(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "INSERT INTO `api_tab_statistiques_site`
                (`date`, `id_user`, `page`, `action`)
                SELECT NOW(),`id`, :page, :nature
                FROM `api_tab_utilisateurs`
                WHERE 1=1
                AND `code_user` = :user
            ;";
            $params->bindsValue = [
                "user" => $this->inputs["user"],
                "page" => $this->inputs["page"],
                "nature" => $this->inputs["action"]
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
    function getReportPageActivity(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT `page`, count(*) 'nombre'
                FROM `api_tab_statistiques_site` a, `api_tab_utilisateurs` b, `api_tab_rangs` c
                WHERE 1=1
                AND a.`id_user` = b.`id`
                AND b.`id_rang` = c.`id`
                AND c.`indice` > 1
                GROUP BY a.`page`
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
    function getAllTheme(){
        try {
            $theme_defaut = $this->getParameter("theme_defaut");

            $params = new stdClass();
            $params->label = "theme";
            if(is_null($theme_defaut)){
                $params->value = "notAvailable";
            }else{
                $params->value = $theme_defaut;
            }
            $this->addDataStr($params);

            //--------------------------------------------------------------------------
            $params = new stdClass();
            $params->nameObj = "api_tab_utilisateurs";
            $params->keyObj = ["code_user" => $this->inputs["code_user"]];
            $params->debug = false;
            $retour = $this->BD_ENGINE->getSingleObject($params);

            $params = new stdClass();
            $params->label = "themePerso";
            if(!isset($retour->theme)){
                $params->value = "notAvailable";
            }else{
                $params->value = $retour->theme;
            }
            $this->addDataStr($params);

            //--------------------------------------------------------------------------
            $path = '../../css/themes/';

            $liste_theme = array();

            $theme = new stdClass();
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
            $this->addDataStr($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
     /**
      */
    function cleanDb(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT 'api_tab_session' as 'table', COUNT(*) as 'nb'
            FROM `api_tab_session`
            UNION
            SELECT 'api_tab_transaction' as 'table', COUNT(*) as 'nb'
            FROM `api_tab_transaction`
            UNION
            SELECT 'api_tab_log' as 'table', COUNT(*) as 'nb'
            FROM `api_tab_log`
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $v_resultats = $this->BD_ENGINE->reqODASQL($params);

            $nb_api_tab_session = intval($v_resultats->data->data[0]->nb);
            $nb_api_tab_transaction = intval($v_resultats->data->data[1]->nb);
            $nb_api_tab_log = intval($v_resultats->data->data[2]->nb);

            $params = new stdClass();
            $params->label = "resultat";
            $params->retourSql = $v_resultats;
            $this->addDataReqSQL($params);

            //--------------------------------------------------------------------------
            $params = new stdClass();
            $params->label = "exec";
            $params->value = $this->inputs["exec"];
            $this->addDataStr($params);

            //--------------------------------------------------------------------------
            $array_purges = array();
            if($this->inputs["exec"] == "true"){
                //--------------------------------------------------------------------------
                // Purge api_tab_session
                $obj_purge = new stdClass();
                $obj_purge->table = "api_tab_session";
                $obj_purge->nb = 0;
                $obj_purge->statut = "none";
                if($nb_api_tab_session > 1000){
                    $obj_purge->statut = "init";

                    $params = new OdaPrepareReqSql();
                    $params->sql = "DELETE
                        FROM `api_tab_session`
                        WHERE 1=1
                        AND `periodeValideMinute` != 0
                        AND `dateCreation` < date_sub(now(), interval 1 month)
                        AND NOW() > date_add(a.`dateCreation`, interval + `periodeValideMinute` minute)
                    ;";
                    $params->typeSQL = OdaLibBd::SQL_SCRIPT;
                    $retour = $this->BD_ENGINE->reqODASQL($params);
                    $obj_purge->nb = $retour->nombre;
                    $obj_purge->statut = "done";
                }
                $array_purges[] = $obj_purge;

                //--------------------------------------------------------------------------
                // Purge api_tab_transaction
                $obj_purge = new stdClass();
                $obj_purge->table = "api_tab_transaction";
                $obj_purge->nb = 0;
                $obj_purge->statut = "none";
                if($nb_api_tab_transaction > 1000){
                    $obj_purge->statut = "init";

                    $params = new OdaPrepareReqSql();
                    $params->sql = "DELETE
                        FROM `api_tab_transaction`
                        WHERE 1=1
                        AND `debut` < date_sub(now(), interval 7 day)
                    ;";
                    $params->typeSQL = OdaLibBd::SQL_SCRIPT;
                    $retour = $this->BD_ENGINE->reqODASQL($params);
                    $obj_purge->nb = $retour->nombre;
                    $obj_purge->statut = "done";
                }
                $array_purges[] = $obj_purge;

                //--------------------------------------------------------------------------
                // Purge api_tab_log
                $obj_purge = new stdClass();
                $obj_purge->table = "api_tab_log";
                $obj_purge->nb = 0;
                $obj_purge->statut = "none";
                if($nb_api_tab_log > 1000){
                    $obj_purge->statut = "init";

                    $params = new OdaPrepareReqSql();
                    $params->sql = "DELETE
                        FROM `api_tab_log`
                        WHERE 1=1
                        AND `dateTime` < date_sub(now(), interval 7 day)
                    ;";
                    $params->typeSQL = OdaLibBd::SQL_SCRIPT;
                    $retour = $this->BD_ENGINE->reqODASQL($params);
                    $obj_purge->nb = $retour->nombre;
                    $obj_purge->statut = "done";
                }
                $array_purges[] = $obj_purge;
            }
            //--------------------------------------------------------------------------
            $params = new stdClass();
            $params->label = "purges";
            $params->value = $array_purges;
            $this->addDataStr($params);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
     /**
      */
    function getReportInterfacMetric(){
        try {
            $params = new OdaPrepareReqSql();
            $params->sql = "SELECT REPLACE(`type`,SUBSTRING_INDEX(`type`, '/', 4), '') as 'interface' 
                ,COUNT(`id`) as 'nb'
                ,COUNT(`id`) *  AVG(TIMEDIFF(`fin`,`debut`)) as 'cost'
                ,AVG(TIMEDIFF(`fin`,`debut`)) as 'average'
                ,MAX(TIMEDIFF(`fin`,`debut`)) as 'maxTime'
                ,MIN(TIMEDIFF(`fin`,`debut`)) as 'minTime'
                FROM  `api_tab_transaction` 
                WHERE 1=1
                AND `fin` != '0000-00-00 00:00:00'
                AND `fin` > NOW() - INTERVAL 7 DAY
                GROUP BY `type`
            ;";
            $params->typeSQL = OdaLibBd::SQL_GET_ALL;
            $retour = $this->BD_ENGINE->reqODASQL($params);
            $this->addDataObject($retour->data->data);
        } catch (Exception $ex) {
            $this->dieInError($ex.'');
        }
    }
}