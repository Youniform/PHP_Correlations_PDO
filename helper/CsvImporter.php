<?php
class CsvImporter {
    function __construct() {
        $this->uploaded_file = $_FILES['historic-csv']['tmp_name'];
        $this->givenName = $_FILES['historic-csv']['name'];
        echo "ion construct:";
    }

    private function dbInteract()
    {
        $databasehost = "localhost";
        $databasename = "index_correlations";

        $databasetable = $this->givenName;

        $databaseusername = "youniform";
        $databasepassword = "il1keSn0w!!";

        $fieldseparator = ",";
        $lineseparator = "\n";

        $csvfile = $this->uploaded_file;


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
        if (!is_null($affectedRows) && $affectedRows > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    public function init() {
        $status = $this->dbInteract();
        $data['status'] = $status;
        require_once("../view/SuccessfulImportView.php"); 
    }
}
