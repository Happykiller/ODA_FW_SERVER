<?php
namespace Oda;

require '../../../../../vendor/autoload.php';
require '../../../../../config/config.php';

$config = SimpleObject\OdaConfig::getInstance();

// script/getResources.php?fic=avatars/ROFA.png
if(isset($_GET["fic"])){
    $path = __DIR__;
    $path = str_replace("vendor".DIRECTORY_SEPARATOR."happykiller".DIRECTORY_SEPARATOR."oda".DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."script",$config->resourcesPath,$path);
    $file = $path . $_GET["fic"];
    if(file_exists($file)){
        $fileName=basename($file);
        $filePath=dirname($file)."/";

        switch(strrchr(basename($fileName), ".")){
            case ".gz": $type = "application/x-gzip"; break;
            case ".tgz": $type = "application/x-gzip"; break;
            case ".zip": $type = "application/zip"; break;
            case ".pdf": $type = "application/pdf"; break;
            case ".png": $type = "image/png"; break;
            case ".gif": $type = "image/gif"; break;
            case ".jpg": $type = "image/jpeg"; break;
            case ".txt": $type = "text/plain"; break;
            case ".htm": $type = "text/html"; break;
            case ".html": $type = "text/html"; break;
            default: $type = "application/octet-stream"; break;
        }

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        header('Access-Control-Max-Age: 86400');
        header("Content-disposition: inline; filename=$fileName");
        header("Content-Type: $type\n"); // ne pas enlever le \n
        header("Content-Length: ".filesize($filePath . $fileName));
        header("Pragma: no-cache");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
        header("Expires: 0");
        readfile($filePath . $fileName);
    }else{
        header("HTTP/1.0 404 Not Found");
    }
}