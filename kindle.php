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
#  0                1                     2                     3                       4                   5           6           7       8              
$count = [];
foreach($csv_array as $line) {
    for ($i=0; $i < count($header); $i++) { 
        $count[$header[$i]][$line[$i]] += 1;
    }
}
#echo "<pre>";
#print_r($count);

include('includes/calendar.php');
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
.grid-item h3 {
  margin:0;
}
</style>

<h1>All Data from all devices</h1>
<?
include('includes/functions.php');

outputCalendar($csv_array);
?>

<hr>

<h1>Data per device:</h1>
<?
$devices = getDevices();
foreach($count['dsn'] as $dsn => $foo) {
    echo "<h1>".$dsn.": ".$devices[$dsn]."</h1>";
    outputCalendar($csv_array, $dsn);
}