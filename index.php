<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("./globals.php");
require_once(HEADER);

$request_uri = $_SERVER["REQUEST_URI"];
$splode = explode( "/",$request_uri);

array_shift($splode);
$r = $splode;
/**
 * Let's name our pieces of the URL for better reading and digestion
 */

$route = $r[0] ?? null;
$param = $r[1] ?? null;
$param = explode("?",$param);
$param = $param[0];

function loadDisplay($param)
{
    if (null === $param) {
        echo "theres a problem with param not being defined";
        return;
    }
    require_once("class/DisplayInfo.php");
    $DI = new DisplayInfo($param);
    $DI->init();
}
function loadImport()
{
    require_once("helper/CsvImporter.php");
    $CI = new CsvImporter();
    $CI->init();
}
function loadHome()
{
    require_once(VIEW."/home.php");
}
switch ($route) {
    case "display": loadDisplay($param);
    break;
    case "import": loadImport();
    break;
    case "": loadHome();
    break;
}