<?php

class DbConn
{
    public function __construct() {
        # REMEMBER TO CREATE ENV.PHP
        /**
         * Use include instead of require method where it counts you fucking jackass
         */
        include("db-config/Env.php");
        $host = "localhost";
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    }
    public function getPdo() {
        return $this->pdo;
    }

}

