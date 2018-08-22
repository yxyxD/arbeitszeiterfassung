<?php

function getDifferenceBetweenTimes(DateTime $startTime, DateTime $endTime)
{
	return date_diff($endTime, $startTime)->format('%H:%I');
}

?>
