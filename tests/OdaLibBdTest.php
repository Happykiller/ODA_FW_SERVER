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
        $conf->BD_ENGINE->base = "null";
        $conf->BD_ENGINE->host = "null";
        $conf->BD_ENGINE->login = "null";
        $conf->BD_ENGINE->mdp = "null";
        $conf->BD_ENGINE->type = "memory";
        $params_bd->bd_conf = $conf->BD_ENGINE;
        $BD_ENGINE = new Oda\OdaLibBd($params_bd);
        $this->assertInstanceOf('Oda\OdaLibBd',$BD_ENGINE);

        $params = new Oda\SimpleObject\OdaPrepareReqSql();
        $params->sql = "CREATE TABLE messages (id INTEGER PRIMARY KEY, title TEXT, message TEXT, time TEXT);";
        $params->typeSQL = Oda\OdaLibBd::SQL_SCRIPT;
        $return = $BD_ENGINE->reqODASQL($params);

        $params = new Oda\SimpleObject\OdaPrepareReqSql();
        $params->sql = "INSERT INTO messages (id, title, message, time) VALUES (1, 'title', 'message', '1327214268');";
        $params->typeSQL = Oda\OdaLibBd::SQL_SCRIPT;
        $return = $BD_ENGINE->reqODASQL($params);

        $params = new Oda\SimpleObject\OdaPrepareReqSql();
        $params->sql = "SELECT * FROM messages;";
        $params->typeSQL = Oda\OdaLibBd::SQL_GET_ALL;
        $return = $BD_ENGINE->reqODASQL($params);

        $this->assertEquals(1,$return->nombre);
    }
}