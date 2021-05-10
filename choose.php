<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * Setting up the database connection for PDO (PHP Data Object)
 */
$dbname = "index_correlations";
$dbuser = "youniform";
$dbpass = "il1keSn0w!!";
$host = "localhost";
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
/**
 * This SQL query will need to incorporate a variable placeholder for the table name
 */
$sql = "
	SHOW TABLES;
	";

/**
 * Prepare the whole $stmt before execution
 */
$stmt = $pdo->prepare($sql);
/**
 * Execute it, sending the finished query to the mysql database
 */
$stmt->execute();

$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tables = $response;
?>

<div class="outer-container">
    <div class="visibility-toggle" tabindex="1">
        <span class="span-label">Choose an existing database table to analyze</span>
        <div class="parent-container">
            <div class="form-div">
                <form name="table-choice" class="table-choice" method="/display" action="POST">
                <label for="tableChoice">Choose Table:</label>
                    <select name="tableChoice">
                    <?php foreach($tables as $table):?>
                    <?php $value = $table; ?>
                        <option name="table-name" value="<?php print_r($value['Tables_in_index_correlations']); ?>"><?php print_r($value['Tables_in_index_correlations']); ?></option>
                    <?php endforeach;?>
                    </select>
                    <input type="submit"/>
                </form>
            </div>
        </div>
    </div>
    <div class="visibility-toggle" tabindex="1">
        <span class="span-label">Choose an CSV file to import to MYSql Table</span>
        <div class="parent-container">
            <div class="form-div">
                <form name="table-choice" class="table-choice" method="/import" action="POST">
                    <label for="fileChoice">Choose File:</label>
                    <input type="file" name="csv-file" />
                </form>
            </div>
        </div>
    </div>
</div>
