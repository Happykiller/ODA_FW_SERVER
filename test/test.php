<?php
namespace Oda;
use stdClass;
//--------------------------------------------------------------------------
require '../../../autoload.php';

//--------------------------------------------------------------------------
$retours = array();

//--------------------------------------------------------------------------
$retours[] = OdaLib::test("get_string_between",function() {
    $v_test = OdaLib::get_string_between("01234", "1", "3");
    OdaLib::equal($v_test, "2", "Test OK : Passed!");
}
);

//--------------------------------------------------------------------------
$resultats = new \stdClass();
$resultats->details = $retours;
$resultats->succes = 0;
$resultats->echec = 0;
$resultats->total = 0;
foreach($retours as $key => $value) {
    $resultats->succes += $value->succes;
    $resultats->echec += $value->echec;
    $resultats->total += $value->total;
}

var_dump($resultats);