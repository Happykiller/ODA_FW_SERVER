<?php
namespace Oda;

require '../../../../../header.php';
require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

use \stdClass, \DateTime, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaConfig;

//vendor/happykiller/oda/resources/script/postResources.php?path=avatars/ Test POST with form-data and put file(s)

$TRANS_STATUS_INIT = "TRANS_STATUS_INIT";
$TRANS_STATUS_ERROR = "TRANS_STATUS_ERROR";
$TRANS_STATUS_SUCCESS = "TRANS_STATUS_SUCCESS";
$TRANS_MSG_PATH_UNDEFINED = "Upload impossible, path resource undefined.";
$TRANS_MSG_PATH_NOT_EXIST = "Upload impossible, path resource not exist.";
$TRANS_MSG_WRONG_EXT = "Wrong extension file supported.";
$TRANS_MSG_FILE_SIZE = "File too big.";
$TRANS_MSG_FILE_EXIST = "File already exist.";
$TRANS_MSG_ERROR_UNKNOWN = "Error unknown.";

$extensions = array('.jpg','.png','.txt','.doc','.docx','.xls','.xlsx','.msg','.pdf');
$fileMax = 5000000; //5Mo

$config = SimpleObject\OdaConfig::getInstance();

//--------------------------------------------------------------------------
$resources = str_replace("vendor".DIRECTORY_SEPARATOR."happykiller".DIRECTORY_SEPARATOR."oda".DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."script", $config->resourcesPath, __DIR__);

//--------------------------------------------------------------------------
//Build the interface
$params = new OdaPrepareInterface();
$ODA_INTERFACE = new OdaLibInterface($params);

$folderDest = (isset($_GET["path"]))?$_GET["path"]:"";

$rewhrite = (isset($_GET["rewhrite"]))?filter_var($_GET["rewhrite"], FILTER_VALIDATE_BOOLEAN):true;

$path = $resources . $folderDest;

if(is_null($config->resourcesPath)){
    $ODA_INTERFACE->dieInError($TRANS_MSG_PATH_UNDEFINED);
} if(!file_exists($path)) {
    $ODA_INTERFACE->dieInError($TRANS_MSG_PATH_NOT_EXIST);
} else {
    foreach ($_FILES as $key => $value) {
        $extension = strrchr($value['name'], '.');
        $extension = strtolower($extension);
        $taille = filesize($value['tmp_name']);

        $msg = new stdClass();
        $msg->fileNameIn = $value["name"];
        if($key != $value["name"]){
            $msg->fileNameOut = $key . $extension;
        }else{
            $msg->fileNameOut = $value["name"];
        }
        $msg->type = $value["type"];
        $msg->size = $value["size"];
        $msg->size = $value["size"];
        $msg->status = $TRANS_STATUS_INIT;
        $msg->msg = "";

        if(!in_array($extension, $extensions)){
            $msg->status = $TRANS_STATUS_ERROR;
            $msg->msg = $TRANS_MSG_WRONG_EXT;
        }

        if($taille>$fileMax) {
            $msg->status = $TRANS_STATUS_ERROR;
            $msg->msg = $TRANS_MSG_FILE_SIZE;
        }

        if(!$rewhrite){
            if(file_exists($path . $msg->name)){
                $msg->status = $TRANS_STATUS_ERROR;
                $msg->msg = $TRANS_MSG_FILE_EXIST;
            }
        }

        if($msg->status == $TRANS_STATUS_INIT){
            if(move_uploaded_file($value['tmp_name'], $path . $msg->fileNameOut)) {
                $msg->status = $TRANS_STATUS_SUCCESS;
            } else {
                $msg->status = $TRANS_STATUS_ERROR;
                $msg->msg = $TRANS_MSG_ERROR_UNKNOWN;
            }
        }

        $params = new stdClass();
        $params->label = $key;
        $params->value = $msg;
        $ODA_INTERFACE->addDataObject($params);
    }
}