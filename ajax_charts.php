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
                 $startTime =  DateTime::createFromFormat('H:i', $workSession['TIME_FROM']);
                 $endTime =  DateTime::createFromFormat('H:i', $workSession['TIME_TO']);
                 $duration = date_diff($startTime, $endTime);

                 $dates[] = $workSession['DATE'];
                 //$sessionDurations[] = $duration->format('%H:%I');
                 $sessionDurations[] = $endTime->getTimestamp() - $startTime->getTimestamp();
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
		$sessionsPerDay = [];
		foreach ($allWorkSessions as $workSession)
		{
			$date = $workSession['DATE'];

			$startTime = DateTime::createFromFormat('H:i:s', $workSession['TIME_FROM']);
			$endTime = DateTime::createFromFormat('H:i:s', $workSession['TIME_TO']);
			$sessionDuration = date_diff($startTime, $endTime);

			$sessionsPerDay[$date][] = $sessionDuration;
		}

		$referenceDuration = new DateTime('00:00');
		$result = [];
		foreach($sessionsPerDay as $date => $sessions)
		{
			$totalDuration = new DateTime('00:00');
			foreach($sessions as $session)
			{
				$totalDuration->add($session);
			}

			$result[$date] = $referenceDuration->diff($totalDuration)->format('%H:%I');
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