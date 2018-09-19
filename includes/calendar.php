<?php

// Source: https://stackoverflow.com/a/5537163/252627

function getDates($minYear, $maxYear, $firstMonth, $lastMonth)
{
    $dates = array();
    
    for($year = $minYear; $year <= $maxYear; $year++){
        date("L", mktime(0,0,0, 7,7, $year)) ? $days = 366 : $days = 365;
        # above from https://stackoverflow.com/a/46735404/252627
        for($i = 1; $i <= $days; $i++){
            $month = date('m', mktime(0,0,0,1,$i,$year));
            $wk = date('W', mktime(0,0,0,1,$i,$year));
            $wkDay = date('D', mktime(0,0,0,1,$i,$year));
            $day = date('d', mktime(0,0,0,1,$i,$year));

            // skip everything before $firstMonth
            if($year == $minYear && $month < $firstMonth) {
                continue;
            }
            // skip everything after $lastMonth
            if($year == $maxYear && $month > $lastMonth) {
                continue;
            }

            #$dates[$month][$wk][$day] = $wkDay;
            $dates[$year][$month][$wk][$wkDay] = $day;
        } 
    }

    return $dates;   
}