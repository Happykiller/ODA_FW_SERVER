<?php
namespace Oda;
use \stdClass, \Oda\SimpleObject\OdaConfig, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;
/**
 * OdaMigration Librairy - main class
 *
 * Tool
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.150803
 */
class OdaMigration {
    /**
     * Content of config.php object $OdaConfig
     *
     * @var OdaConfig
     */
    protected static $config;
    /**
     * All details of the interface
     *
     * @var \ArrayObject
     */
    protected $params;
    /**
     * The bd engine
     *
     * @var OdaLibBd
     */
    protected $BD_ENGINE;
    /**
     * class constructor
     *
     * @param stdClass $p_params
     * @return OdaDate $this
     */
    public function __construct($p_params = NULL){
        self::$config = SimpleObject\OdaConfig::getInstance();
        self::$config->isOK();
        $params_bd = new stdClass();
        $params_bd->bd_conf = self::$config->BD_ENGINE;
        $this->BD_ENGINE = new OdaLibBd($params_bd);
        $this->params = $p_params;
    }
    /**
     * Destructor
     *
     * @access public
     * @return null
     */
    public function __destruct(){
        
    }

    /*
     * @return OdaMigration
     */
    public function migrate(){
        try {
            $this->isOk();

            if($this->params['partial'] !== "all"){
                $this->exe('./'.$this->params['target'].'/'.$this->params['partial'].'/'.$this->params['option'].'.sql');
            }else{
                $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('./'.$this->params['target'].'/', \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
                foreach($objects as $name => $object){
                    if ($object->isDir()) {
                        $name = str_replace('\\','/',$name);
                        $this->exe($name.'/'.$this->params['option'].'.sql');
                    }
                }
            }

            echo 'Sucess' . PHP_EOL;

            return $this;
        } catch (Exception $ex) {
            die($ex.'');
        }
    }
    /**
     * isOK
     *
     * @access public
     * @return boolean
     */
    public function isOK(){
        try {
            if (is_null($this->params)) {
                print "Options are missing." . PHP_EOL;
                die(1);
            }

            if (!isset($this->params['target']) ) {
                print "There was a problem reading in the options." . PHP_EOL;
                die(1);
            }

            if (!isset($this->params['option']) ) {
                $this->params['option'] = "do";
            }

            if (!isset($this->params['partial']) ) {
                $this->params['partial'] = "all";
            }

            if (!isset($this->params['checkDb']) ) {
                $this->params['checkDb'] = false;
            }
            return true;
        } catch (Exception $ex) {
            die($ex.'');
        }
    }
    /**
     * exe
     *
     * @access public
     * @return boolean
     */
    public function exe($file){
        try {
            echo "Script selected : ". $file . PHP_EOL;

            if($this->params['checkDb']){
                $params = new OdaPrepareReqSql();
                $params->sql = "
                    SELECT COUNT(*) as 'nb'
                    FROM `".self::$config->BD_ENGINE->prefixTable."api_tab_migration`
                    WHERE 1=1
                    AND `name` = '".$file."'
                ";
                $params->typeSQL = OdaLibBd::SQL_GET_ONE;
                $retour = $this->BD_ENGINE->reqODASQL($params);
                $exist = $retour->data->nb;

                if($exist && ($this->params['option'] == "do")){
                    echo "Status for the migration : already done" . PHP_EOL;
                    return true;
                }

                if(!$exist && ($this->params['option'] == "unDo")){
                    echo "Status for the migration : nothing to unDo" . PHP_EOL;
                    return true;
                }
            }

            $contentScript = file_get_contents($file, FILE_USE_INCLUDE_PATH);

            $contentScript = str_replace("@prefix@", self::$config->BD_ENGINE->prefixTable, $contentScript);

            $params = new OdaPrepareReqSql();
            $params->sql = $contentScript;
            $params->typeSQL = OdaLibBd::SQL_SCRIPT;
            $retour = $this->BD_ENGINE->reqODASQL($params);

            echo "Status for the migration : " . $retour->strStatut . (($retour->strStatut != 5) ? (" (error : " . $retour->strErreur . ")") : "")    . PHP_EOL;
            if($this->params['option'] == "do"){
                $params = new OdaPrepareReqSql();
                $params->sql = "
                    INSERT INTO `".self::$config->BD_ENGINE->prefixTable."api_tab_migration`
                    (`name`, `dateMigration`)
                    VALUES
                    ('".$file."', NOW())
                ";
                $params->typeSQL = OdaLibBd::SQL_SCRIPT;
                $retour = $this->BD_ENGINE->reqODASQL($params);
                echo "Status for the trace record : " . $retour->strStatut . (($retour->strStatut != 5) ? (" (error : " . $retour->strErreur . ")") : "")    . PHP_EOL;
            }elseif($this->params['option'] == "unDo"){
                $params = new OdaPrepareReqSql();
                $file = str_replace('unDo','do',$file);
                $params->sql = "
                    DELETE FROM `".self::$config->BD_ENGINE->prefixTable."api_tab_migration`
                    WHERE 1=1
                    AND `name` = '".$file."'
                ";
                $params->typeSQL = OdaLibBd::SQL_SCRIPT;
                $retour = $this->BD_ENGINE->reqODASQL($params);
                echo "Status for the trace record : " . $retour->strStatut . (($retour->strStatut != 5) ? (" (error : " . $retour->strErreur . ")") : "")    . PHP_EOL;
            }
            return $this;
        } catch (Exception $ex) {
            die($ex.'');
        }
    }
}