<?php

/**
 * File is a combination config file for global values like paths
 * as well as where I'll define PHP CONSTANT definitions for now
 */
$dir = getcwd();
define("PRJ_ROOT", __DIR__ );
define("VIEW", PRJ_ROOT . "/view");
define("HEADER",VIEW . "/header.php");
define("CLASSDIR", PRJ_ROOT . "/class");
define("HELPER",PRJ_ROOT ."/helper");