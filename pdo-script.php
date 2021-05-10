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
$upAndUpFilterGt05 = array();
// Array that holds trends from After Down, Close Down for day
$downAndDown = array();
// Auto-Incremented value for each loop iteration if it discovers a day where it trended up or down
$trendUp = 0;
$trendDown = 0;
$trendUpFilterGt05 = 0;
$afterHrsChgUpTotal05 = 0;

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
        $afterHrsChg = $day->Open - $response[$yesterday]->Close;
        $afterHrsChgUpPct = $afterHrsChg / $response[$yesterday]->Close*100;
        $amountChgUpUp = $day->Close - $day->Open;
        $pctChgUpUp = $amountChgUpUp / $day->Open *100;
        $amountChgDownDown = $day->Open - $day->Close;
        $pctChgDownDown = $amountChgDownDown / $day->Open * 100;
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
			if ($up and $dayTrendUp) {
			    $day->pctChgUpUp = $pctChgUpUp;
			    array_push($upAndUp, $day);
            }
            if ($afterHrsChgUpPct > 0.5) {
                $afterHrsChgUpTotal05++;
                if ($dayTrendUp && $afterHrsChgUpPct > 0.5) {
                    $day->amountChgUpUp = $amountChgUpUp;
                    $day->pctChgUpUp = $pctChgUpUp;
                    $day->afterHrsChg = $afterHrsChg;
                    $day->afterHrsChgUpPct = $afterHrsChgUpPct;
                    array_push($upAndUpFilterGt05, $day);
                }
            }

		}
		// If the after hours started down for that day, auto increment the trendDown value
		if ($down) {
			$trendDown++;
			if ($dayTrendDown) {
			    $day->pctChgDownDown = $pctChgDownDown;
			    array_push($downAndDown, $day);
            }
		}
	}

	$i++;
}

// a couple stat calculations to show for the year
$upPercent = count($upAndUp)/$trendUp*100;
$upAndUpFilterGt05Percent = count($upAndUpFilterGt05)/$afterHrsChgUpTotal05*100;
$downPercent = count($downAndDown)/$trendDown*100;
$data = array(
    "Total-Sample-Size" => count($response),
    "OvernightUp+DayCloseUp-Percent:" => floor($upPercent*100)/100 . "%",
    "OverNightUp-Filter-GT-05-DayCloseUp-Percent" => floor($upAndUpFilterGt05Percent*100)/100 . "%",
    "OvernightDown+DayCloseDown-Percent:" => floor($downPercent*100)/100 . "%",
    "TrendUp-Count:" => $trendUp,
    "TrendDown-Count:" => $trendDown,
    "DownAndDown-Total:" =>count($downAndDown),
    "UpAndUp-Total:" =>count($upAndUp)
);

// echo makes things appear on the page, the quoted areas are some text or html markup
require_once("./header.php");
?>
<div class="outer-container">
    <div class="visibility-toggle ui-bg-darkest ui-font-lightest value" tabindex="1">
        <span class="span-label"><?php echo $tableName;?> [ click to show ]</span>
        <div class="parent-container">
            <?php foreach($data as $key=>$value):?>
                <div class="ui-bg-darkest ui-font-lightest key"><span><?php echo $key;?></span></div>
                <?php if (100 >= $value && 50< $value):?>
                    <div class="trendIndicator"><span><?php echo $value;?></span></div>
                <?php else:?>
                    <div class="ui-bg-lightest ui-font-darkest value"><span><?php echo $value; ?></span></div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    </div>
    <div class="visibility-toggle" tabindex="1">
        <div class="ui-bg-lightest ui-font-darkest value"><span class="span-label"><?php echo $tableName . "Afters Hours Up trend to Day Close Also Up | Filter: AfterHoursChange > 0.5%"; ?> <?php echo count($upAndUpFilterGt05); ?></span></div>
        <div class="parent-container">
            <table id="upAndUpFilterGt05" name="upAndUpFilterGt05">
                <th>After Hours Chg</th>
                <th>After Hours Pct</th>
                <th>Day Close Change</th>
                <th>Day Close Pct</th>
    <?php foreach ($upAndUpFilterGt05 as $day):?>
        <tr>
            <td><?php echo floor($day->afterHrsChg*100)/100;?></td>
            <td><?php echo floor($day->afterHrsChgUpPct*100)/100;?></td>
            <td><?php echo floor($day->amountChgUpUp*100)/100;?></td>
            <td><?php echo floor($day->pctChgUpUp*100)/100;?></td>
        </tr>
    <?php endforeach;?>
        </table>
        </div>
    </div>
</div>
