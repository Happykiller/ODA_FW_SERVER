<?php
/**
 * Created by PhpStorm.
 * User: HAPPYBONITA
 * Date: 26/04/2016
 * Time: 13:34
 */

class OdaLibBdTest extends \PHPUnit_Framework_TestCase {
    public function test__construct() {
        $params_bd = new stdClass();
        $conf = new Oda\SimpleObject\OdaConfig();
        $params_bd->bd_conf = $conf->BD_ENGINE;
        $params_bd->modeDebug = true;
        $BD_ENGINE = new Oda\OdaLibBd($params_bd);
        $this->assertInstanceOf('OdaLibBd',$BD_ENGINE);
    }
}