<?php
error_reporting(E_ALL);
ini_set('display_errors','1');

/**
* Setting up the database connection for PDO (PHP Data Object)
*/
$dbname = "index_correlations";
$dbuser = "youniform";
$dbpass = "il1keSn0w!!";
$host = "localhost";	
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
$tableName = "DIA-entire-history";
/**
* This SQL query will need to incorporate a variable placeholder for the table name
*/
$sql = "
	SELECT * FROM  `$tableName`
	";

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
$response = $stmt->fetchall(PDO::FETCH_OBJ);

/**
* Array Holders for Potential Correlations
* In PHP you need to define $variables that you want to reference in many functions OUTSIDE of the declaration
* Of those functions or else you will end up editing it too many times, or not being able to access it to use elsewhere
*/

// Array that holds trends from After Up, Close Up for day
$upAndUp = array();
// Array that holds trends from After Down, Close Down for day
$downAndDown = array();
// Auto-Incremented value for each loop iteration if it discovers a day where it trended up or down
$trendUp = 0;
$trendDown = 0;

// iterator loop value, meaning this is what count out of 0 to 250 (meaning 251 days computers start at 0 like civilized people)
$i = 0;

// for every row ($day) that was returned with the $response object. We could've technically called $day or $row
// this is where you set the $varname for how you'll reference each iteration within the loop.
foreach ($response as $day) {
	if ($i === 0) {
		// something better than this can fill this do nothing area, its just to not fall into a pitfall for 0
		echo "do nothing";
	}
	else {
		// subtract one from current iteration value in order to reference yesterday using array nothing $array[$i] 
		// for an array $foo = [20,36,"a string",false] if you wanted to get the value of $foo[1] it would reference 36
		// $foo[2] would reference the third index which is "a string"
		$yesterday = $i - 1;
		$prevDay = $response[$yesterday];

		/**
		* Here I am creating $variables that hold a binary value (0 = false, 1=true)
		* With computers it is much easier to make complex assertions by breaking them down into simpler yes/no
		*/
		$up = $day->Open > $prevDay->Close;
		$down = $prevDay->Close > $day->Open;
		$dayTrendUp = $day->Close > $day->Open;
		$dayTrendDown = $day->Open > $day->Close;

		// If the after hours started up for that day, auto increment the trendUp value
		if ($up) {
			$trendUp++;
		}		
		// If the after hours started down for that day, auto increment the trendDown value
		if ($down) {
			$trendDown++;
		}
		// If after hours started day up and day close was up, file that into our $upAndUp array
		if ($up && $dayTrendUp) {
			array_push($upAndUp, $day);

		}
		// If after hours started day down and day close was down, file that into our $downAndDown array
		if ($down && $dayTrendDown ) {
			array_push($downAndDown, $day);
		}
	}
	$i++;
}

// a couple stat calculations to show for the year
$upPercent = count($upAndUp)/$trendUp*100;
$downPercent = count($downAndDown)/$trendDown*100;
$data = array(
    "Total-Sample-Size" => count($response),
    "OvernightUp+DayCloseUp-Percent:" => floor($upPercent*100)/100 . "%",
    "OvernightDown+DayCloseDown-Percent:" => floor($downPercent*100)/100 . "&",
    "TrendUp-Count:" => $trendUp,
    "TrendDown-Count:" => $trendDown,
    "DownAndDown-Total:" =>count($downAndDown),
    "UpAndUp-Total:" =>count($upAndUp)
);

// echo makes things appear on the page, the quoted areas are some text or html markup
require_once("./header.php");
?>
<div class="outer-container">
    <div class="data-container">
            <h3><?php echo $tableName;?></h3>
            <?php foreach($data as $key=>$value):?>
                <div class="ui-bg-darkest ui-font-lightest key"><span><?php echo $key;?></span></div>
                <?php if (100 >= $value && 52 <= $value):?>
                    <div class="trendIndicator"><span><?php echo $value;?></span></div>
                <?php else:?>
                    <div class="ui-bg-lightest ui-font-darkest value"><span><?php echo $value; ?></span></div>
                <?php endif;?>
            <?php endforeach;?>
    </div>
</div>
<h3>Results from 2020-2021 Dow Jones Industrial Average</h3>";

Total population size:" . count($response) . "<br/>";
echo "AfterHoursUp and DayCloseUp: " . count($upAndUp)  . "<br/>  Occurance: " . floor($upPercent*100)/100 . "%<br/>";
echo "AfterHoursDown and DayCloseDown" . count($downAndDown) . "<br/>  Occurance: " . floor($downPercent*100)/100 . "%<br/>";


