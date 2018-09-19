<?php

include('path.php');

$filename = 'Kindle/Inhalte/KindleReadingActions.csv';
$file = $path.'/'.$filename;

#$content = file_get_contents($file);
#echo $content;

$csv_array = array_map('str_getcsv', file($file));

#echo "<pre>";
#print_r($csv_array);

// count unique values
$header = array_shift($csv_array);
$count = [];
foreach($csv_array as $line) {
    for ($i=0; $i < count($header); $i++) { 
        $count[$header[$i]][$line[$i]] += 1;
    }
}
echo "<pre>";
print_r($count);