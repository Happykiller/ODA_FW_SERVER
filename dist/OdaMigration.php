<?php
namespace Oda;
use \stdClass, \Oda\SimpleObject\OdaConfig, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBdn, \JBZoo\Utils\Str, \JBZoo\Utils\Filter;
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

            if(isset($this->params['auto'])){
                print "Auto mode selected." . PHP_EOL;

                $params = new OdaPrepareReqSql();
                $params->sql = "SELECT `param_value`
                    FROM `".self::$config->BD_ENGINE->prefixTable."api_tab_parametres`
                    WHERE 1=1
                    AND `param_name` = 'install_date'
                ";
                $params->typeSQL = OdaLibBd::SQL_GET_ONE;
                $retour = $this->BD_ENGINE->reqODASQL($params);

                if($retour->data){

                    $installDate = $retour->data->param_value;
                    $compressInstallDate = Filter::int(Str::sub($installDate, 2, 2) . Str::sub($installDate, 5, 2) . Str::sub($installDate, 8, 2));

                    print "Install date is: " . $installDate . PHP_EOL;

                    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('.' . DIRECTORY_SEPARATOR, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
                    foreach($objects as $folderPath => $object){
                        if ($object->isDir()) {
                            $filePath = $folderPath . DIRECTORY_SEPARATOR  . 'do.sql';
                            if(file_exists($filePath)){
                                $banned_words = "-install -reworkModel -matrixRangApi";
                                if (!(preg_match('~\b(' . str_replace(' ', '|', $banned_words) . ')\b~', $filePath)) && (preg_match('/[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])/',$filePath))) {
                                    $patchDate = Filter::int(Str::sub($filePath, 6, 6));
                                    if($patchDate > $compressInstallDate){
                                        $this->exe($filePath);
                                    }
                                }
                            }
                        }
                    }
                }else{
                    print "No install_date retrieve." . PHP_EOL;
                }
            }else{
                print "Target mode selected." . PHP_EOL;

                if($this->params['partial'] !== "all"){
                    $this->exe('.'.DIRECTORY_SEPARATOR.$this->params['target'].DIRECTORY_SEPARATOR.$this->params['partial'].DIRECTORY_SEPARATOR.$this->params['option'].'.sql');
                }else{
                    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('.' . DIRECTORY_SEPARATOR . $this->params['target'], \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
                    foreach($objects as $name => $object){
                        if ($object->isDir()) {
                            $this->exe($name.DIRECTORY_SEPARATOR.$this->params['option'].'.sql');
                        }
                    }
                }
            }

            echo 'Success' . PHP_EOL;

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
            if (is_null($this->params)||empty($this->params)) {
                print "Options are missing." . PHP_EOL;
                print "|__ 'auto' => ex: --auto" . PHP_EOL;
                print "|__ 'target' => ex: --target=000-install | all." . PHP_EOL;
                print "   |__ 'partial', optional => ex: --partial=000-useful" . PHP_EOL;
                print "   |__ 'option', optional => ex: --option=do | unDo" . PHP_EOL;
                print "   |__ 'checkDb', optional => ex: --checkDb" . PHP_EOL;
                die(1);
            }

            if ((!isset($this->params['auto'])&&(!isset($this->params['target'])))) {
                print "Option 'Target' and 'auto' is missing." . PHP_EOL;
                die(1);
            }

            if ((isset($this->params['target'])&&(empty($this->params['target'])))) {
                print "Option 'Target' is not defined." . PHP_EOL;
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

            if (isset($this->params['auto']) ) {
                $this->params['checkDb'] = true;
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
                    AND `name` = '".str_replace('\\', '/', $file)."'
                ";
                $params->typeSQL = OdaLibBd::SQL_GET_ONE;
                $retour = $this->BD_ENGINE->reqODASQL($params);
                $exist = $retour->data->nb;

                if($exist && ($this->params['option'] == "do")){
                    echo "Status for the migration: $file: already done" . PHP_EOL;
                    return true;
                }else if(!$exist && ($this->params['option'] == "do")){
                    echo "Status for the migration: $file: clear to done" . PHP_EOL;
                }

                if(!$exist && ($this->params['option'] == "unDo")){
                    echo "Status for the migration: $file: nothing to unDo" . PHP_EOL;
                    return true;
                }else if($exist && ($this->params['option'] == "unDo")){
                    echo "Status for the migration: $file: check ok to unDo" . PHP_EOL;
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
                    ('".str_replace('\\', '/', $file)."', NOW())
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
                    AND `name` = '".str_replace('\\', '/', $file)."'
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