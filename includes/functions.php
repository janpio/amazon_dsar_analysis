<?php

function getDevices() {
    global $path;
    $devices = [];

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
            $count[$header[$i]][$line[$i]] += 1;
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