<?php

function getDifferenceBetweenTimes($startTime, $endTime)
{

    $start = DateTime::createFromFormat('H:i', $startTime);
    $end = DateTime::createFromFormat('H:i', $endTime);

	return date_diff($end, $start)->format('%H:%I');
}

?>
