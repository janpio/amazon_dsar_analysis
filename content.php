<meta charset="utf-8">
<?php
include('includes/path.php');
include('includes/functions.php');

$foldername = 'Kindle/Inhalte/digitalcontentownership';
$folder = $path.'/'.$foldername;

$files = scandir($folder);
$files = array_diff($files, array('.', '..'));

$asinCache = file_get_contents('cache/asins.json');
$asins = json_decode($asinCache, true);

foreach($files as $file) {
    $content = file_get_contents($folder.'/'.$file);
    $array = json_decode($content, true);
    $items[] = [
        'type' => $array['resource']['resourceType'],
        'asin' => $array['resource']['resourceId'],
        'name' => $asins[$array['resource']['resourceId']],
        'acquired' => $array['rights'][0]['acquiredDate'],
        'parent' => $array['resource']['parent']['resourceId'],
        'origin' => $array['rights'][0]['origin']['originType']
    ];    
}

$count = countUniqueValues2($items);
#echo "<pre>";
#print_r($count);
#exit;

echo "<table>";
echo "<tr><td>Type</td><td>ASIN</td><td>Name</td><td>Acquired</td><td>Parent</td><td>Origin</td></tr>\n";
foreach($items as $item) {
    echo "<tr><td>".$item['type']."</td><td>".$item['asin']."</td><td>".$item['name']."</td><td>".$item['acquired']."</td><td>".$item['parent']."</td><td>".$item['origin']."</td></tr>\n";
    if(!$asins[$item['asin']]) {
        $asins[$item['asin']] = '';
    }
}
echo "</table>";

print_r($asins);
$asinCache = json_encode($asins, JSON_PRETTY_PRINT);
echo $asinCache;
file_put_contents('cache/asins.json', $asinCache);