<?php

$data = $this->data;
echo "<pre>";
print_r($data["figures"]);
echo "</pre>";
?>
<div class="outer-container">
    <div class="visibility-toggle ui-bg-darkest ui-font-lightest value" tabindex="1">
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
        <div class="ui-bg-lightest ui-font-darkest value"><span class="span-label"><?php echo $data['tableName'] . "Afters Hours Up trend to Day Close Also Up | Filter: AfterHoursChange > 0.5%"; ?> <?php echo "broken"; ?></span></div>
        <div class="parent-container">
            <table id="upAndUpFilterGt05" name="upAndUpFilterGt05">
                <th>After Hours Chg</th>
                <th>After Hours Pct</th>
                <th>Day Close Change</th>
                <th>Day Close Pct</th>
                <?php foreach ($data['tables']['DayTrendFilterGt05UpUp'] as $day):?>
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
