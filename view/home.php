<?php

/**
 * Setting up the database connection for PDO (PHP Data Object)
 */
require_once(CLASSDIR . "/DbConn.php");
$dbClass = new DbConn();
$pdo = $dbClass->getPdo();
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
                <form name="table-choice" class="table-choice" method="POST" action="/display">
                    <label for="tableChoice">Choose Table:</label>
                    <div class="tableChoice">
                    <?php foreach($tables as $table):?>
                    <?php echo '<a href=/display/'.$table["Tables_in_index_correlations"].'>'.$table["Tables_in_index_correlations"].'</a><br/>';?>
                    <?php endforeach;?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="visibility-toggle" tabindex="1">
        <span class="span-label">Choose a CSV file to import to MySQL Table</span>
        <div class="parent-container">
            <div class="form-div">
                <form name="table-choice" class="table-choice" method="POST" action="/import" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                    <label for="csv-file">CSV Files, sourced exclusively from finance.yahoo.com Historical Data</label>
                    <input type="file" name="csv-file" />
                    <input type="submit" value="Send File"/>
                </form>
            </div>
        </div>
    </div>
</div>
