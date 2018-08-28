<?php

function getDifferenceBetweenTimes($startTime, $endTime)
{

    $start = DateTime::createFromFormat('H:i:s', $startTime);
    $end = DateTime::createFromFormat('H:i:s', $endTime);
	return date_diff($end, $start)->format('%H:%I');
}

?>
