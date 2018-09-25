<?php

function getDevices() {
    global $path;
    $devices = [];
    $devices[""] = "Unknown Device";

    $filename = 'Kindle/Geräte/registration.csv';
    $file = $path.'/'.$filename;
    $csv_array = array_map('str_getcsv', file($file));

    # DeviceSerialNumber	DeviceAccountRole	AccountName	FirstTimeRegistered		LastTimeRegistered		TimeDeregistered		CustomerType	State	CurrentFirmwareVersion	DeviceModel	localTimeOffset	ipAddress	CUSTOMER_LOGIN_POOL													
    #       0                       1               2               3                       4                       5                      6
    foreach($csv_array as $line) {
        $devices[$line[0]] = $line[2];
    }

    return $devices;
}

function countUniqueValues($csv_array) {
    $header = array_shift($csv_array);
    $count = [];
    foreach($csv_array as $line) {
        for ($i=0; $i < count($header); $i++) { 
            if(!isset($count[$header[$i]])) $count[$header[$i]] = [];
            if(!isset($count[$header[$i]][$line[$i]])) $count[$header[$i]][$line[$i]] = 0;
            $count[$header[$i]][$line[$i]] += 1;
        }
    }
    return $count;
}

function countUniqueValues2($array) {
    $count = [];
    foreach($array as $item) {
        foreach($item as $key => $value) {
            if(!isset($count[$key])) $count[$key] = [];
            if(!isset($count[$key][$value])) $count[$key][$value] = 0;
            $count[$key][$value] += 1;
        }
    }
    return $count;
}

function extractDate($date) {
    $months = ['', "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    # Sun May 08 09:47:24 UTC 2011
    $year = substr($date, -4);
    $month = str_pad(array_search(substr($date, 4, 3), $months), 2, 0, STR_PAD_LEFT);
    $day = substr($date, 8, 2);
    return $year.'-'.$month.'-'.$day;
}

function extractDate2($date) {
    $months = ['', "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

    # TueMay1021:19:28UTC2011
    # RIGHT(C2;4)
    # MATCH(MID(LEFT(C2;8);4;3);{"Jan";"Feb";"Mar";"Apr";"May";"Jun";"Jul";"Aug";"Sep";"Oct";"Nov";"Dec"};0)
    # RIGHT(LEFT(C2;8);2))
    $year = substr($date, -4);
    $month = str_pad(array_search(substr($date, 3, 3), $months), 2, 0, STR_PAD_LEFT);
    $day = substr($date, 6, 2);
    return $year.'-'.$month.'-'.$day;
}

function extractDate3($date) {
    $months = ['', "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

    # 26/Jan/2017 20:03:14 UTC
    # MID(D2;8;4)
    # MATCH(MID(D2;4;3);{"Jan";"Feb";"Mar";"Apr";"May";"Jun";"Jul";"Aug";"Sep";"Oct";"Nov";"Dec"};0)
    # LEFT(D2;2)
    $year = substr($date, 7, 4);
    $month = str_pad(array_search(substr($date, 3, 3), $months), 2, 0, STR_PAD_LEFT);
    $day = substr($date, 0, 2);
    #echo $year.'-'.$month.'-'.$day;
    return $year.'-'.$month.'-'.$day;
}

