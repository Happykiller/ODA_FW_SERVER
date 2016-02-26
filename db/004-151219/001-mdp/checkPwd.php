<?php
/**
 * Created by PhpStorm.
 * User: Happykiller
 * Date: 19/12/2015
 * Time: 14:44
 */
echo "Enter your password unencrypted : ";
$handle = fopen ("php://stdin","r");
$password = rtrim(fgets($handle));

echo "Enter your password encrypted : ";
$handle = fopen ("php://stdin","r");
$hash = rtrim(fgets($handle));

if(password_verify($password, $hash)){
    echo 'OK'."\n";
}else{
    echo 'KO'."\n";
}

echo "\n";
echo "Thank you.\n";