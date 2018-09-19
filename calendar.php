<?php

include('includes/calendar.php');


$dates = getDates(2017, 2018, 7, 6); 

$weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'); ?>
<?php foreach($dates as $year => $months) { ?>
    <?php echo "<h1>$year</h1>"; ?>
    <?php foreach($months as $month => $weeks) { ?>
        <?php echo "<h2>$month</h2>"; ?>
        <table>
            <tr>
                <th><?php echo implode('</th><th>', $weekdays); ?></th>
            </tr>
            <?php foreach($weeks as $week => $days){ ?>
            <tr>
                <?php foreach($weekdays as $day){ ?>
                <td>
                    <?php echo isset($days[$day]) ? $days[$day] : '&nbsp'; ?>
                </td>               
                <?php } ?>
            </tr>
            <?php } ?>
        </table>
    <?php } ?>
<?php } ?>