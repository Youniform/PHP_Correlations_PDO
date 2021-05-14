<?php
require_once("../class/BaseClass.php");
new BaseClass();
class CsvImporter extends BaseClass {
    __construct() {
        $this->uploaded_file = $_FILES['historic-csv']['tmp_name'];
        $this->givenName = $_FILES['historic-csv']['name';
    }
    private function test_given_name($givenName) {
        $wash = $this->sterilize($givenName);
        return $wash;
    }

    private function db_interact($tableName)
    {
        var_dump($tableName);
        $databasehost = "localhost";
        $databasename = "index_correlations";

        $databasetable = "";

        $databaseusername = "test";
        $databasepassword = "";

        $fieldseparator = ",";
        $lineseparator = "\n";

        $csvfile = $uploaded_file;


        if (!file_exists($csvfile)) {
            die("File not found. Make sure you specified the correct path.");
        }


        try {

            $pdo = new PDO(
                "mysql:host=$databasehost;dbname=$databasename",
                $databaseusername,
                $databasepassword,
                array
                (
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        } catch (PDOException $e) {
            die("database connection failed: " . $e->getMessage());
        }

        $affectedRows = $pdo->exec
        (
            "LOAD DATA LOCAL INFILE "
            . $pdo->quote($csvfile)
            . " INTO TABLE `$databasetable` FIELDS TERMINATED BY "
            . $pdo->quote($fieldseparator)
            . "LINES TERMINATED BY "
            . $pdo->quote($lineseparator)
        );
        echo "Loaded a total of $affectedRows records from this csv file.\n";
    }
}