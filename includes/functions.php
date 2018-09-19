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
