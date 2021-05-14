<?php
$request_uri = $_SERVER["REQUEST_URI"];
$splode = explode( "/",$request_uri);
var_dump($splode);

$r = array_shift($splode);

if ($r[0] === "display" && is_string($r[1])) {
    require_once("class/DisplayInfo.php");
    $DI = new DisplayInfo();
    $tableName = $r[1];
    $DI->getDisplay($tableName);
}

