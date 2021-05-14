<?php
class RasnepAfterHours {
    function __construct($response) {
        $this->response = $response;
        // Arrays that hold trends from After Up, Close Up for day
        $this->upAndUp = array();
        $this->upAndUpFilterGt05 = array();
            // Array that holds trends from After Down, Close Down for day
        $this->downAndDown = array();
            // Auto-Incremented value for each loop iteration if it discovers a day where it trended up or down
        $this->trendUp = 0;
        $this->trendDown = 0;
        $this->afterHrsChgUpTotal05 = 0;
        $this->init();
    }
    private function originalCalculations($response) {

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
                $this->trendUp++;
                if ($up and $dayTrendUp) {
                    $day->pctChgUpUp = $pctChgUpUp;
                    array_push($this->upAndUp, $day);
                }
                if ($afterHrsChgUpPct > 0.5) {
                    $this->afterHrsChgUpTotal05++;
                    if ($dayTrendUp && $afterHrsChgUpPct > 0.5) {
                        $day->amountChgUpUp = $amountChgUpUp;
                        $day->pctChgUpUp = $pctChgUpUp;
                        $day->afterHrsChg = $afterHrsChg;
                        $day->afterHrsChgUpPct = $afterHrsChgUpPct;
                        array_push($this->upAndUpFilterGt05, $day);
                    }
                }

            }
            // If the after hours started down for that day, auto increment the trendDown value
            if ($down) {
                $this->trendDown++;
                if ($dayTrendDown) {
                    $day->pctChgDownDown = $pctChgDownDown;
                    array_push($this->downAndDown, $day);
                }
            }
        }

        $i++;
    }

    // a couple stat calculations to show for the year
    $upPercent = count($this->upAndUp)/$this->trendUp*100;
    $this->upAndUpFilterGt05Percent = count($this->upAndUpFilterGt05)/$this->afterHrsChgUpTotal05*100;
    $downPercent = count($this->downAndDown)/$this->trendDown*100;
    $data = array(
        "figures"=> array(
            "Total-Sample-Size" => count($response),
            "OvernightUp+DayCloseUp-Percent:" => floor($upPercent*100)/100 . "%",
            "OverNightUp-Filter-GT-05-DayCloseUp-Percent" => floor($this->upAndUpFilterGt05Percent*100)/100 . "%",
            "OvernightDown+DayCloseDown-Percent:" => floor($downPercent*100)/100 . "%",
            "TrendUp-Count:" => $this->trendUp,
            "TrendDown-Count:" => $this->trendDown,
            "DownAndDown-Total:" =>count($this->downAndDown),
            "UpAndUp-Total:" =>count($this->upAndUp)
            ),
        "tables"=> array(
            "DayTrendFilterGt05UpUp" => $this->upAndUpFilterGt05
        ));
    return $data;
    }
    public function init(){
        return $this->originalCalculations($this->response);
    }
}