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
}