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
    exe('./'.$options['target'].'/'.$options['partial'].'/'.$options['option'].'.sql');
}else{
    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('./'.$options['target'].'/', \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
    foreach($objects as $name => $object){
        if ($object->isDir()) {
            exe($name.'/'.$options['option'].'.sql');
        }
    }
}

echo 'Sucess' . PHP_EOL;

function exe($file){
    $config = OdaConfig::getInstance();

    echo "Script selected : ". $file . PHP_EOL;

    $contentScript = file_get_contents($file, FILE_USE_INCLUDE_PATH);

    $contentScript = str_replace("@prefix@", $config->BD_ENGINE->prefixTable, $contentScript);

    $params_bd = new stdClass();
    $params_bd->bd_conf = $config->BD_ENGINE;
    $BD_ENGINE = new OdaLibBd($params_bd);

    $params = new OdaPrepareReqSql();
    $params->sql = $contentScript;
    $params->typeSQL = OdaLibBd::SQL_GET_ONE;
    $retour = $BD_ENGINE->reqODASQL($params);
}