<?php

include('includes/path.php');
include('includes/functions.php');

$filename = 'Kindle/Inhalte/whispersync.csv';
$file = $path.'/'.$filename;

#$content = file_get_contents($file);
#echo $content;

$csv_array = array_map('str_getcsv', file($file));
#echo "<pre>";
#print_r($csv_array);
#exit;

// count unique values
# Asin	Annotation Type	Customer modified date on device		DSN	ContentType	Format	Creation Date		LastUpdatedDate		Highlight	Version	Selection Type	Note	Is Deleted
#  0         1      2                  3                        4        5        6           7                 8                   9

$count = countUniqueValues($csv_array);
#echo "<pre>";
#print_r($count);
#exit;

include('includes/calendar.php');
?>

<h1><?php echo $filename; ?></h1>

<h2>All Data from all devices</h2>
<?
outputCalendar(prepareForCalendar2($csv_array, 2));
exit;
?>

<hr>

<h2>Data per device:</h2>
<?
$devices = getDevices();
foreach($count['device_serial_number'] as $dsn => $foo) {
    echo "<h1>".$dsn.": ".$devices[$dsn]."</h1>";
    outputCalendar(prepareForCalendar2($csv_array, 2, $dsn));
}