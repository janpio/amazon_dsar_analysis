<?php

include('includes/path.php');
include('includes/functions.php');

$filename = 'Kindle/Inhalte/ReadingSessions.csv';
$file = $path.'/'.$filename;

#$content = file_get_contents($file);
#echo $content;

$csv_array = array_map('str_getcsv', file($file));
#echo "<pre>";
#print_r($csv_array);

// count unique values
# device_serial_number	device_software_version	reader_software_version	content_type	contentid	is_amazon_book	start_date	end_date	total_reading_millis														
#           0                1                             2                     3           4           5            6            7              8              
$count = countUniqueValues($csv_array);
#echo "<pre>";
#print_r($count);

include('includes/calendar.php');
?>

<h1><?php echo $filename; ?></h1>

<h2>All Data from all devices</h2>
<?php
outputCalendar(prepareForCalendar($csv_array, 6));
?>

<hr>

<h2>Data per device:</h2>
<?php
$devices = getDevices();
foreach($count['device_serial_number'] as $dsn => $foo) {
    echo "<h1>".$dsn.": ".$devices[$dsn]."</h1>";
    outputCalendar(prepareForCalendar($csv_array, 6, $dsn));
}