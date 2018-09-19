<?php

function getDevices() {
    global $path;
    $devices = [];

    $filename = 'Kindle/Geräte/registration.csv';
    $file = $path.'/'.$filename;
    $csv_array = array_map('str_getcsv', file($file));

    # DeviceSerialNumber	DeviceAccountRole	AccountName	FirstTimeRegistered		LastTimeRegistered		TimeDeregistered		CustomerType	State	CurrentFirmwareVersion	DeviceModel	localTimeOffset	ipAddress	CUSTOMER_LOGIN_POOL													
    #       0                       1               2               3                       4                       5                      6
    foreach($csv_array as $line) {
        $devices[$line[0]] = $line[2];
    }

    return $devices;
}


function outputCalendar($csv_array, $dsn = null) {
    // get days with activity
    # datatimestamp = 8
    $dates = [];
    foreach($csv_array as $line) {
        if($dsn && $dsn != $line[0]) {
            continue;
        }
        $date = substr($line[8], 0, 10);
        $dates[$date] += 1;
    }
    #print_r($dates);
    $min = min(array_keys($dates));
    $max = max(array_keys($dates));

    // output as calendar
    $minYear = substr($min, 0, 4);
    $maxYear = substr($max, 0, 4);
    $firstMonth = substr($min, 5, 2);
    $lastMonth = substr($max, 5, 2);
    $calendarDates = getDates($minYear, $maxYear, $firstMonth, $lastMonth); 
    $weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'); 
    echo '<div class="grid-container">';
    foreach($calendarDates as $year => $months) {
        echo "<div style='grid-column: 1 / span 6;'><h2>$year</h2></div>"; ?>    
        <?php foreach($months as $month => $weeks) { ?>
            <div class="grid-item" 
                style="<?php if($firstMonth != 1 && ($year == $minYear && $month == $firstMonth)) { echo "grid-column-start: ".($month % 6).";"; } ?>"
                >
            <?php echo "<h3>$month</h3>"; ?>
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