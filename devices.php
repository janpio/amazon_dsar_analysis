<meta charset="utf-8">
<?php
include('includes/path.php');
include('includes/functions.php');

$filename = 'Kindle/Geräte/registration.csv';
$file = $path.'/'.$filename;

$csv_array = array_map('str_getcsv', file($file));
#echo "<pre>";
#print_r($csv_array);

// count unique values
# DeviceSerialNumber	DeviceAccountRole	AccountName	FirstTimeRegistered		LastTimeRegistered		TimeDeregistered		CustomerType	State	CurrentFirmwareVersion	DeviceModel	localTimeOffset	ipAddress	CUSTOMER_LOGIN_POOL													
#       0                       1               2               3                       4                       5                      6
$count = countUniqueValues($csv_array);
#echo "<pre>";
#print_r($count);

$devices = [];
array_shift($csv_array);
foreach($csv_array as $line) {
    $start = extractDate($line[3]);
    if($line[5] != 'null') {
        $end = extractDate($line[5]);
    } else {
        $end = date('Y-m-d');
    }
    $devices[] = ['dsn' => $line[0], 'name' => $line[2], 'start' => $start, 'end' => $end];
}
$devices = array_map("unserialize", array_unique(array_map("serialize", $devices)));
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet" type="text/css" />

<div id="visualization"></div>

<script type="text/javascript">
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');

  // Create a DataSet (allows two way data-binding)
  var items = new vis.DataSet([
    <?php
    foreach($devices as $device) {
        echo "{content: '".addslashes($device['name'])."', start: '".$device['start']."', end: '".$device['end']."'},\n";
    }
    ?>
  ]);

  // Configuration for the Timeline
  var options = {};

  // Create a Timeline
  var timeline = new vis.Timeline(container, items, options);
</script>

<?php
echo "<table>";
foreach($devices as $device) {
    echo "<tr><td>".$device['dsn']."</td><td>".$device['name']."</td></tr>\n";
}
echo "</table>";