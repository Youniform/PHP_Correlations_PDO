<?php

$data = $this->data;
echo "<pre>";
print_r($data["figures"]);
echo "</pre>";
?>
<div class="outer-container">
    <div class="visibility-toggle" tabindex="1">
        <span class="span-label"><?php echo $data['tableName'];?> [ click to show ]</span>
        <div class="parent-container">
            <?php foreach($data["figures"] as $key=>$value):?>
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
        <div class="turn-grey">
            <span class="span-label"><?php echo $data['tableName']; ?></span>
            <p>Days that opened up, and also closed up.</p>
        </div>
        <script>
            function openClose(element) {
                let toolkit = element.parentElement;
                if (toolkit.classList.contains("showing")) {
                    toolkit.classList.remove("showing");
                }
                else {
                    toolkit.classList.add("showing");
                }
            }
        </script>
        <div class="parent-container">
            <div class="toolkit">
                <div class="tool-panel"></div>
                <div class="arrow-right" onclick="openClose(this)"></div>
                <div class="clear"></div>
            </div>
            <table id="upAndUpFilterGt05" name="upAndUpFilterGt05">
                <th style="padding:8px;">Day of Week</th>
                <th style="padding:8px;">After Hours Chg</th>
                <th style="padding:8px;">After Hours Pct</th>
                <th style="padding:8px;">Day Close Change</th>
                <th style="padding:8px;">Day Close Pct</th>
                <?php foreach ($data['tables']['DayTrendFilterGt05UpUp'] as $day):?>
                    <tr>
                        <td <?php echo "class=$day->dayOfWeek"; ?>><?php echo $day->dayOfWeek;?></td>
                        <td <?php echo "class=$day->dayOfWeek"; ?>><?php echo floor($day->afterHrsChg*100)/100;?></td>
                        <td <?php echo "class=$day->dayOfWeek"; ?>><?php echo floor($day->afterHrsChgUpPct*100)/100;?></td>
                        <td <?php echo "class=$day->dayOfWeek"; ?>><?php echo floor($day->amountChgUpUp*100)/100;?></td>
                        <td <?php echo "class=$day->dayOfWeek"; ?>><?php echo floor($day->pctChgUpUp*100)/100;?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
</div>
