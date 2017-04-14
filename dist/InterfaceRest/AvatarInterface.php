<?php
namespace Oda\InterfaceRest;

use Exception;
use \stdClass;

/**
 * Project class
 *
 * Tool
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.1170412
 */
class AvatarInterface {

    protected static $config;

    public function __construct(){
        try {
            self::$config = \Oda\SimpleObject\OdaConfig::getInstance();
        } catch (Exception $ex) {
            die($ex.'');
        }
    }

    /**
     */
    function getAvatar($userCode) {
        try {
            $path = __DIR__;
            $width = 80;
            if(isset($_GET["w"])){
                $width = $_GET["w"];
            }
            $height = 80;
            if(isset($_GET["h"])){
                $height = $_GET["h"];
            }
            $pathOda = str_replace("dist".DIRECTORY_SEPARATOR."InterfaceRest", "resources".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR, $path);
            $noAvatar = $pathOda . "no_avatar.png";
            if(self::$config->resourcesLink == null){
                $pathApp = str_replace("vendor".DIRECTORY_SEPARATOR."happykiller".DIRECTORY_SEPARATOR."oda".DIRECTORY_SEPARATOR."dist".DIRECTORY_SEPARATOR."InterfaceRest", self::$config->resourcesPath."avatars".DIRECTORY_SEPARATOR, $path);
                $avatarApp = $pathApp . $userCode . ".png";
                if(file_exists($avatarApp)){
                    $im = $this->resize_image($avatarApp,$width,$height);
                }else{
                    header("Content-type: image/png");
                    $im = $this->resize_image($noAvatar,$width,$height);
                }
            }else{
                $pathApp = self::$config->resourcesLink."avatars".DIRECTORY_SEPARATOR;
                $avatarApp = $pathApp . $userCode . ".png";
            }
            header("Content-type: image/png");
            imagepng($im);
            imagedestroy($im);
            die();
        } catch (Exception $ex) {
            die($ex.'');
        }
    }

    function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefrompng($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending( $dst, false );
        imagesavealpha( $dst, true );
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagedestroy($src);
        return $dst;
    }
}