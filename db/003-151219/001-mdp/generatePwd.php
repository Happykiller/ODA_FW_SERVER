<?php
/**
 * Created by PhpStorm.
 * User: Happykiller
 * Date: 19/12/2015
 * Time: 14:44
 */
echo "Enter your password : ";
$handle = fopen ("php://stdin","r");
$password = rtrim(fgets($handle));
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash."\n";
echo "\n";
echo "Thank you.\n";