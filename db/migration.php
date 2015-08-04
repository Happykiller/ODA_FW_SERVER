<?php
namespace Oda;

require '../../../../vendor/autoload.php';
require '../../../../include/config.php';

use \stdClass;

// php migration.php --target=000-install --partial=001-migration --option=do

$shortopts  = "";

$longopts  = array(
    "target:",
    "partial::",
    "option::",
    "checkDb::"
);
$options = getopt($shortopts, $longopts);

$OdaMigration = new OdaMigration($options);

$OdaMigration->migrate();