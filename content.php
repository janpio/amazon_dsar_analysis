<meta charset="utf-8">
<?php
include('includes/path.php');
include('includes/functions.php');

$foldername = 'Kindle/Inhalte/digitalcontentownership';
$folder = $path.'/'.$foldername;

$files = scandir($folder);
$files = array_diff($files, array('.', '..'));

foreach($files as $file) {
    $content = file_get_contents($folder.'/'.$file);
    $array = json_decode($content, true);
    $items[] = [
        'type' => $array['resource']['resourceType'],
        'asin' => $array['resource']['resourceId'],
        'name' => '',
        'acquired' => $array['rights'][0]['acquiredDate'],
        'parent' => $array['resource']['parent']['resourceId'],
    ];    
}

echo "<table>";
echo "<tr><td>Type</td><td>ASIN</td><td>Name</td><td>Acquired</td><td>Parent</td></tr>\n";
foreach($items as $item) {
    echo "<tr><td>".$item['type']."</td><td>".$item['asin']."</td><td>".$item['name']."</td><td>".$item['acquired']."</td><td>".$item['parent']."</td></tr>\n";
}
echo "</table>";