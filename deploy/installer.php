<?php
require "../dist/OdaLib.php";

/**
 * Created by PhpStorm.
 * User: HAPPYBONITA
 * Date: 20/07/2015
 * Time: 13:40
 */

$shortopts  = "";

$longopts  = array(
    "test::"
);
$options = getopt($shortopts, $longopts);

\Oda\OdaLib::recurse_copy("default","../../../../");