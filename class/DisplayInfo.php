<?php
Class DisplayInfo {
    function __construct(string $tableName)
    {
        // This can't be safe to take $tableName as a $_POST or $_GET URI_REQUEST , how to sanitize or not use at all?
        // Haha just kidding, there's no functionality at all to have a placeholder for table in SELECT * FROM table
        // the fucking fuck? safety first boys!!
        $this->tableName = $tableName;
        $this->data = "";
    }

    private function dbConn()
    {
        /**
         * Setting up the database connection for PDO (PHP Data Object)
         * Function is set as private in order to not be invoked by accident from something unintended like a different controller function
         */
        $dbname = "index_correlations";
        $dbuser = "youniform";
        $dbpass = "il1keSn0w!!";
        $host = "localhost";
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
        return $pdo;
    }

    private function query(string $tableName, $pdo)
    {
        /**
         * This SQL query will need to incorporate a variable placeholder for the table name
         */
        $sql = "SELECT * FROM  `$tableName`";

        /**
         * Prepare the whole $stmt before execution
         */
        $stmt = $pdo->prepare($sql);

        /**
         * Execute it, sending the finished query to the mysql database
         */
        $stmt->execute();

        /**
         * Form an iterable $response object from the executed $stmt. FetchAll meaning the whole thing not just one row, PDO::FETCH_OBJ
         * allows us to refer to each $day like this object notation: $day->Open vs associative array: $day["Open"]
         */
        $response = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (!empty($response)) {
            return $response;
        } else {
            return "Response variable is empty/null";
        }
    }

    public function init()
    {
        require_once(CLASSDIR."/DbConn.php");
        $DB = new DbConn();
        $pdo = $DB->getPdo();
        $response = $this->query($this->tableName, $pdo);
        if (null === $response) {
            echo "response empty";
            die();
        }
            // pull in the thinking function
        require_once(PRJ_ROOT."/oge/RasnepAfterHours.php");
        $rasnep = new RasnepAfterHours($response);
        $this->data = $rasnep->init();
        $this->data["tableName"] = $this->tableName;
        require_once(VIEW."/DisplayInfoView.php");
    }
}
