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

                 $dates[] = $workSession['DATE'];
                 $sessionDurations[] = $endTime->getTimestamp() - $startTime->getTimestamp();
             }

             $returnArray['dates'] = $dates;
             $returnArray['sessionDurations'] = $sessionDurations;
        }

        echo json_encode($returnArray);
    }

    // Chart that contains the ratio of days you worked and did not work on the project during the project time
    function createWorkDaysRatioData()
	{
		$projectID = $_GET['projectID'];
		$projectData = selectProjectData($projectID);
		$dateStart = DateTime::createFromFormat('Y-m-d', $projectData['DATE_START']);
		$dateEnd = DateTime::createFromFormat('Y-m-d', $projectData['DATE_END']);

		$result = [];
		if(($dateStart !== false) && ($dateEnd !== false))
		{
			$allWorkSessions = selectAllWorkSessions($projectID);
			$dates = [];
			foreach($allWorkSessions as $workSession)
			{
				$dates[$workSession['DATE']] = "";
			}

			$projectDuration = date_diff($dateStart, $dateEnd)->days;
			$daysWorked = sizeof($dates);

			$result['labels'] = ['Tage gearbeitet', 'Tage nicht gearbeitet'];
			$result['data'] = [$daysWorked, $projectDuration - $daysWorked];
		}

		echo json_encode($result);
	}

	// Chart that contains the ratio of days where you worked longer and shorter than desired
	function createWorkTimeRatioData()
	{
		$projectID = $_GET['projectID'];
		$projectData = selectProjectData($projectID);
		$desiredDaylyWorkTime = DateTime::createFromFormat(
			'H:i',
			$projectData['DESIRED_DAYLY_WORKTIME']
		)->getTimestamp() - DateTime::createFromFormat('H:i', '00:00')->getTimestamp();

		$result = [];
		if($desiredDaylyWorkTime !== false)
		{
			$daylySessionDuration = [];

			$allWorkSessions = selectAllWorkSessions($projectID);
			foreach($allWorkSessions as $workSession)
			{
				$date = $workSession['DATE'];
				$startTime = DateTime::createFromFormat('H:i', $workSession['TIME_FROM']);
				$endTime = DateTime::createFromFormat('H:i', $workSession['TIME_TO']);

				$daylySessionDuration[$date] += $endTime->getTimestamp() - $startTime->getTimestamp();
			}

			foreach($daylySessionDuration as $key => $value)
			{
				$result['dates'][] = $key;

				if(($value - $desiredDaylyWorkTime) <= 0)
				{
					$result['sessionDurations'][0] += 1;
				}
				else
				{
					$result['sessionDurations'][1] += 1;
				}
			}
			$result['labels'] = ['ohne Überstunden', 'mit Überstunden'];
		}

		echo json_encode($result);
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


    function performAjaxRequest()
    {
        if(isSessionActive() && isset($_GET['workSessionChart'])) { createWorkTimeData(); }
		if(isSessionActive() && isset($_GET['workDaysRatioChart'])) { createWorkDaysRatioData(); }
		if(isSessionActive() && isset($_GET['workTimeRatioChart'])) { createWorkTimeRatioData(); }
    }
?>

<?php 	// Execute all ajax functions
    performAjaxRequest();
?>