<?php
ini_set('max_execution_time', 0);

$asinCache = file_get_contents('cache/asins.json');
$asinArray = json_decode($asinCache, true);
echo count($asinArray).' ASINs in cache file<br>';

# filter non-empty entries
$emptyAsins = array_filter($asinArray, function($x) { return empty($x); });
echo count($emptyAsins).' ASINs do not have any content yet<br>';
$asins = array_keys($emptyAsins);
shuffle($asins);

$asinErrorCache = file_get_contents('cache/asinErrors.json');
$asinErrorArray = json_decode($asinErrorCache, true);
echo count($asinErrorArray).' ASINs in error cache<br>';
echo "<hr>";

$i = 0;
foreach($asins as $asin) {
    # skip asins that previously errored
    if($asinErrorArray[$asin]) {
        continue;
    }

    echo "<h2>$asin</h2>";

    $url = "https://www.amazon.de/dp/".$asin;
    $amazon = @file_get_contents($url);

    if ($amazon !== false) {
        
        preg_match('/\<title\>(.*)\<\/title\>/', $amazon, $matches);
        if($matches[1]) {
            echo "success 1";
            preg_match('/(.*) eBook: (.*): Amazon.de: Kindle-Shop/', $matches[1], $matches2);
            print_r($matches2);

            if($matches2[1]) {
                $title = $matches2[1].'; '.$matches2[2];
            } else {
                $title = $matches[1];
            }
            $asinArray[$asin] = $title;
        } else {
            if(strpos($amazon, "Geben Sie die Zeichen unten ein")) {
                echo "Captcha :(";
                exit;
            }
        }
    } else {
        echo "error 1 :(";

        $url = "https://www.amazon.de/product-reviews/".$asin;
        $amazon2 = @file_get_contents($url);
    
        if ($amazon2 !== false) {
            
            preg_match('/\<title\>(.*)\<\/title\>/', $amazon2, $matches);
            if($matches[1]) {
                echo "success 2";
                $title = str_replace('Amazon.de:Kundenrezensionen: ', '', $matches[1]);
                
                preg_match('/\<a class="a-size-base a-link-normal" href="([^"]*)"\>([^\<]*)\<\/a\>/', $amazon2, $matches2);
                if($matches2[2]) {
                    $title .= "; ".$matches2[2];
                }
                $asinArray[$asin] = $title;
            } else {
                if(strpos($amazon, "Geben Sie die Zeichen unten ein")) {
                    echo "Captcha :(";
                    exit;
                }
            }

        } else {
            echo "error 2 :(";
            $error = error_get_last();
            $error = explode(': ', $error['message']);
            $error = trim($error[2]) . PHP_EOL;        
            $asinErrorArray[$asin] = $error;
        }
    }

    $i++;
    if($i == 30) {
        break;
    }
}

#print_r($asinArray);

echo "<hr>";
$emptyAsins = array_filter($asinArray, function($x) { return empty($x); });
echo count($emptyAsins).' ASINs are still without content<br>';

$asinCache = json_encode($asinArray, JSON_PRETTY_PRINT);
file_put_contents('cache/asins.json', $asinCache);

if($asinErrorArray) {
    $asinErrorCache = json_encode($asinErrorArray, JSON_PRETTY_PRINT);
    file_put_contents('cache/asinErrors.json', $asinErrorCache);
}
echo count($asinErrorArray).' now ASINs in error cache<br>';

#echo $asinCache;