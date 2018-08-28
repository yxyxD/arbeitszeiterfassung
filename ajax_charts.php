<?php
    /*
     * #######################
     * Session handling
     * #######################
     */
    session_start();


    /*
     * #######################
     * PHP includes
     * #######################
     */
    include 'mysql.php';
?>

<?php

    /*
     * #######################
     * Ajax Methods
     * #######################
     */

    function createWorkTimeData()
    {
        $projectID = $_GET['projectID'];
        $allWorkSessions = selectAllWorkSessions($projectID);

        $returnArray = [];
        if ($allWorkSessions !== false)
        {
             $dates = [];
             $sessionDurations = [];
             foreach ($allWorkSessions as $workSession)
             {
                 $startTime =  DateTime::createFromFormat('H:i:s', $workSession['TIME_FROM']);
                 $endTime =  DateTime::createFromFormat('H:i:s', $workSession['TIME_TO']);
                 $duration = date_diff($startTime, $endTime);

                 $dates[] = $workSession['DATE'];
                 $sessionDurations[] = $duration->format('%H:%I');
             }

             $returnArray['dates'] = $dates;
             $returnArray['sessionDurations'] = $sessionDurations;
             $returnArray['test'] = getWorkTimeOfDailySessionsCombined($allWorkSessions);
        }

        echo json_encode($returnArray);
    }

    /*
     * #######################
     * Helper Methods
     * #######################
     */

    function isSessionActive()
    {
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];

        $isSessionActive = true;
        if(!isset($user_id) && !isset($user_name))
        {
            $isSessionActive = false;
        }

        return $isSessionActive;
    }

    function getWorkTimeOfDailySessionsCombined($allWorkSessions)
	{
		$result = [];

		$lastDate = null;
		$date = null;
		$referenceDuration = new DateTime('00:00');
		$totalDuration = new DateTime('00:00');
		foreach ($allWorkSessions as $workSession)
		{
			$date = DateTime::createFromFormat('Y-m-d', $workSession['DATE']);
			if($lastDate === null) { $lastDate = clone $date; }

			$startTime =  DateTime::createFromFormat('H:i:s', $workSession['TIME_FROM']);
			$endTime =  DateTime::createFromFormat('H:i:s', $workSession['TIME_TO']);
			$sessionDuration = date_diff($startTime, $endTime);

			if($date === $lastDate)
			{
				$totalDuration->add($sessionDuration);
			}
			else
			{
				$result[$date->format('Y-m-d')] = $referenceDuration->diff($totalDuration)->format('%H:%I');
				$totalDuration = new DateTime('00:00');
			}
		}

		return $result;
	}

    function performAjaxRequest()
    {
        if(isSessionActive() && isset($_GET['workSessionChart'])) { createWorkTimeData(); }
    }
?>

<?php 	// Execute all ajax functions
    performAjaxRequest();
?>