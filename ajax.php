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
	 include 'helper.php';
?>



<?php
	/* 
	 * #######################
	 * Ajax methods
	 * #######################
	 */

	function saveNewWorkSession()
	{
		$entranceDate = DateTime::createFromFormat(
			'Y-m-d', date('Y-m-d', time())
		)->format('Y-m-d');
		$workDuration = getDifferenceBetweenTimes($_GET['startTime'], $_GET['endTime']);

		$newSessionID = insertNewWorkSession(
			$_GET['projectID'],
			$entranceDate,
			$_GET['startTime'],
			$_GET['endTime'],
			$_GET['comment']
		);

		$returnArray = [];
		if($newSessionID !== false)
		{
			$returnArray['sessionId'] = $newSessionID;
			$returnArray['entranceDate'] = $entranceDate;
			$returnArray['startTime'] = $_GET['startTime'];
			$returnArray['endTime'] = $_GET['endTime'];
			$returnArray['duration'] = $workDuration;
			$returnArray['comment'] = $_GET['comment'];
		}
		
		echo json_encode($returnArray);
	}

	function deleteWorkSession()
	{
        deleteWorkSessionFromDatabase($_GET['sessionID']);
	}

	function updateWorkSession()
	{
        $workDuration = getDifferenceBetweenTimes($_GET['startTime'], $_GET['endTime']);

        $workSessionUpdated = updateWorkSessionInDatabase(
			$_GET['sessionID'],
			$_GET['startTime'],
			$_GET['endTime'],
			$_GET['comment']
		);

        $returnArray = [];
        if ($workSessionUpdated !== false)
		{
            $returnArray['sessionId'] = $_GET['sessionID'];
            $returnArray['startTime'] = $_GET['startTime'];
            $returnArray['endTime'] = $_GET['endTime'];
            $returnArray['duration'] = $workDuration;
            $returnArray['comment'] = $_GET['comment'];

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

	function performAjaxRequest()
	{
		if(isSessionActive() && isset($_GET['newWorkSession'])) { saveNewWorkSession(); }
		if(isSessionActive() && isset($_GET['deleteWorkSession'])) { deleteWorkSession(); }
		if(isSessionActive() && isset($_GET['updateWorkSession'])) { updateWorkSession(); }
	}
?>

<?php 	// Execute all ajax functions
	performAjaxRequest();
?>
