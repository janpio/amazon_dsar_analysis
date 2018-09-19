<?php
include('includes/path.php');

$filename = 'Kindle/Geräte/registration.csv';
$file = $path.'/'.$filename;

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