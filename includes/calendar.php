<?php

// Source: https://stackoverflow.com/a/5537163/252627

function getDates($minYear, $maxYear, $firstMonth, $lastMonth)
{
    #echo $minYear." -> ".$maxYear;
    #echo $firstMonth." -> ".$lastMonth;

    $dates = array();
    
    for($year = $minYear; $year <= $maxYear; $year++){
        date("L", mktime(0,0,0, 7,7, $year)) ? $days = 366 : $days = 365;
        # above from https://stackoverflow.com/a/46735404/252627
        for($i = 1; $i <= $days; $i++){
            $month = date('m', mktime(0,0,0,1,$i,$year));
            $wk = date('W', mktime(0,0,0,1,$i,$year));
            $wkDay = date('D', mktime(0,0,0,1,$i,$year));
            $day = date('d', mktime(0,0,0,1,$i,$year));

            // skip everything before $firstMonth
            if($year == $minYear && $month < $firstMonth) {
                continue;
            }
            // skip everything after $lastMonth
            if($year == $maxYear && $month > $lastMonth) {
                continue;
            }

            #$dates[$month][$wk][$day] = $wkDay;
            $dates[$year][$month][$wk][$wkDay] = $day;
        } 
    }

    return $dates;   
}

function prepareForCalendar($data, $index, $dsn = null) {
    array_shift($data);

    // get days with activity
    $dates = [];
    foreach($data as $line) {
        if($dsn && $dsn != $line[0]) {
            continue;
        }
        $date = substr($line[$index], 0, 10);
        $dates[$date] += 1;
    }
    $min = min(array_keys($dates));
    $max = max(array_keys($dates));

    $minYear = substr($min, 0, 4);
    $maxYear = substr($max, 0, 4);
    $firstMonth = substr($min, 5, 2);
    $lastMonth = substr($max, 5, 2);

    return compact('dates', 'minYear', 'maxYear', 'firstMonth', 'lastMonth');
}

function prepareForCalendar2($data, $index, $dsn = null) {
    array_shift($data);

    // get days with activity
    $dates = [];
    foreach($data as $line) {
        if($dsn && $dsn != $line[0]) {
            continue;
        }
        $date = extractDate2($line[$index]);
        $dates[$date] += 1;
    }
    $min = min(array_keys($dates));
    $max = max(array_keys($dates));

    $minYear = substr($min, 0, 4);
    $maxYear = substr($max, 0, 4);
    $firstMonth = substr($min, 5, 2);
    $lastMonth = substr($max, 5, 2);

    return compact('dates', 'minYear', 'maxYear', 'firstMonth', 'lastMonth');
}

function outputCalendar($data) {
    extract($data);
        
    // output as calendar
    $calendarDates = getDates($minYear, $maxYear, $firstMonth, $lastMonth); 
    $weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'); 
    echo '<div class="grid-container">';
    foreach($calendarDates as $year => $months) {
        echo "<div style='grid-column: 1 / span 6;'><h3>$year</h23></div>"; ?>    
        <?php foreach($months as $month => $weeks) { ?>
            <div class="grid-item" 
                style="<?php if($firstMonth != 1 && ($year == $minYear && $month == $firstMonth)) { echo "grid-column-start: ".($month % 6).";"; } ?>"
                >
            <?php echo "<h4>$month</h4>"; ?>
            <table>
                <tr>
                    <th><?php echo implode('</th><th>', $weekdays); ?></th>
                </tr>
                <?php foreach($weeks as $week => $days){ ?>
                <tr>
                    <?php foreach($weekdays as $day){ ?>
                    <td style="<?php if($dates[$year.'-'.$month.'-'.$days[$day]]) { echo "background-color:green;"; } ?>">
                        <?php echo isset($days[$day]) ? $days[$day] : '&nbsp'; ?>
                    </td>               
                    <?php } ?>
                </tr>
                <?php } ?>
            </table></div>
        <?php } 
    } 
    echo "</div>";
}

?>

<style>
.grid-container {
  display: inline-grid;
  grid-template-columns: auto auto auto auto auto auto;
  grid-gap: 10px;
}
.grid-item {
  background-color: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(0, 0, 0, 0.8);
  padding: 10px;
}
.grid-item h4 {
  margin:0;
}
</style>