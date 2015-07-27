<?php
namespace Oda;

require '../../../../header.php';
require '../../../../vendor/autoload.php';
require '../../../../include/config.php';

use \stdClass, \Oda\SimpleObject\OdaConfig, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd;

// php migration.php --target=000-install --partial=001-migration --option=do

$shortopts  = "";

$longopts  = array(
    "target:",
    "partial::",
    "option::"
);
$options = getopt($shortopts, $longopts);

$config = OdaConfig::getInstance();

$params_bd = new stdClass();
$params_bd->bd_conf = $config->BD_ENGINE;
$BD_ENGINE = new OdaLibBd($params_bd);

if (!isset($options['target']) ) {
    print "There was a problem reading in the options." . PHP_EOL;
    exit(1);
}

// TODO ajouter notion db on install tout ce qui n'est pas encore installer sauf 000
if (!isset($options['option']) ) {
    $options['option'] = "do";
}

if (!isset($options['partial']) ) {
    $options['partial'] = "all";
}

if($options['partial'] !== "all"){
    exe($config, $BD_ENGINE, './'.$options['target'].'/'.$options['partial'].'/'.$options['option'].'.sql', $options);
}else{
    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('./'.$options['target'].'/', \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
    foreach($objects as $name => $object){
        if ($object->isDir()) {
            $name = str_replace('\\','/',$name);
            exe($config, $BD_ENGINE, $name.'/'.$options['option'].'.sql', $options);
        }
    }
}

echo 'Sucess' . PHP_EOL;

function exe($config, $BD_ENGINE, $file, $options){
    echo "Script selected : ". $file . PHP_EOL;

    $contentScript = file_get_contents($file, FILE_USE_INCLUDE_PATH);

    $contentScript = str_replace("@prefix@", $config->BD_ENGINE->prefixTable, $contentScript);

    $params = new OdaPrepareReqSql();
    $params->sql = $contentScript;
    $params->typeSQL = OdaLibBd::SQL_SCRIPT;
    $retour = $BD_ENGINE->reqODASQL($params);

    echo "Status for the migration : " . $retour->strStatut . (($retour->strStatut != 5) ? (" (error : " . $retour->strErreur . ")") : "")    . PHP_EOL;
    if($options['option'] == "do"){
        $params = new OdaPrepareReqSql();
        $params->sql = "
            INSERT INTO `".$config->BD_ENGINE->prefixTable."api_tab_migration`
            (`name`, `dateMigration`)
            VALUES
            ('".$file."', NOW())
        ";
        $params->typeSQL = OdaLibBd::SQL_SCRIPT;
        $retour = $BD_ENGINE->reqODASQL($params);
        echo "Status for the trace record : " . $retour->strStatut . (($retour->strStatut != 5) ? (" (error : " . $retour->strErreur . ")") : "")    . PHP_EOL;
    }elseif($options['option'] == "unDo"){
        $params = new OdaPrepareReqSql();
        $file = str_replace('unDo','do',$file);
        $params->sql = "
            DELETE FROM `".$config->BD_ENGINE->prefixTable."api_tab_migration`
            WHERE 1=1
            AND `name` = '".$file."'
        ";
        $params->typeSQL = OdaLibBd::SQL_SCRIPT;
        $retour = $BD_ENGINE->reqODASQL($params);
        echo "Status for the trace record : " . $retour->strStatut . (($retour->strStatut != 5) ? (" (error : " . $retour->strErreur . ")") : "")    . PHP_EOL;
    }
}