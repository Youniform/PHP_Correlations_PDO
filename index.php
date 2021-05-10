<?php
$request_uri = $_SERVER["REQUEST_URI"];
$splode = explode( "/",$request_uri);
var_dump($splode);
require_once("header.php");
require_once("choose.php");
