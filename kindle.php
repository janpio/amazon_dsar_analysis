<?php

include('includes/path.php');

$filename = 'Kindle/Inhalte/KindleReadingActions.csv';
$file = $path.'/'.$filename;

#$content = file_get_contents($file);
#echo $content;

$csv_array = array_map('str_getcsv', file($file));
#echo "<pre>";
#print_r($csv_array);

// count unique values
$header = array_shift($csv_array);
# dsn	device_software_version	reader_software_version	message_datetime	message_creation_timestamp	actionid	contentid	context	datatimestamp	spantype	pointtype	settingid	state
$count = [];
foreach($csv_array as $line) {
    for ($i=0; $i < count($header); $i++) { 
        $count[$header[$i]][$line[$i]] += 1;
    }
}
#echo "<pre>";
#print_r($count);

// get days with activity
# datatimestamp = 8
$dates = [];
foreach($csv_array as $line) {
    $date = substr($line[8], 0, 10);
    $dates[$date] += 1;
}
#print_r($dates);
$min = min(array_keys($dates));
$max = max(array_keys($dates));
#echo $min;
#echo $max;

#echo "</pre>";

include('includes/calendar.php');

$calendarDates = getDates(substr($min, 0, 4), substr($max, 0, 4), substr($min, 5, 2), substr($max, 5, 2)); 
$weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'); ?>
<?php foreach($calendarDates as $year => $months) { ?>
    <?php echo "<h1>$year</h1>"; ?>
    
    <div class="grid-container">
    <?php foreach($months as $month => $weeks) { ?>
        <div class="grid-item" style="<?php if(substr($min, 5, 2) != 1 && ($year == substr($min, 0, 4) && $month == substr($min, 5, 2))) { echo "grid-column-start: $month;"; } ?>">
        <?php echo "<h2>$month</h2>"; ?>
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
    <?php } ?>
    </div>
<?php } ?>

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
.grid-item h2 {
    margin:0;
}
</style>