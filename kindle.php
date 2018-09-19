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
echo "<pre>";
print_r($count);

// get days with activity
# datatimestamp = 8
$dates = [];
foreach($csv_array as $line) {
    $date = substr($line[8], 0, 10);
    $dates[$date] += 1;
}
print_r($dates);
$min = min(array_keys($dates));
$max = max(array_keys($dates));
echo $min;
echo $max;