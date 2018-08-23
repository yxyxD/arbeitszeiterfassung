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
	 * Ajax methods
	 * #######################
	 */

	function performAjaxRequest(){
        if(isSessionActive() && isset($_GET['newWorkSession'])) { saveNewWorkSession(); }
        if(isSessionActive() && isset($_GET['deleteWorkSession'])) { deleteWorkSession(); }
	}

	function saveNewWorkSession()
	{
		if(!isSessionActive() && !isset($_GET['newWorkSession'])) { return; }
		
	
		$projectID = $_GET['projectID'];
		$entranceDate = DateTime::createFromFormat('Y-m-d', date('Y-m-d', time()));
		$startTime =  DateTime::createFromFormat('H:i', $_GET['startTime']);
		$endTime =  DateTime::createFromFormat('H:i', $_GET['endTime']);
		$comment = $_GET['comment'];
		
		$workDuration = date_diff($endTime, $startTime);

		$newSessionID = insertNewWorkSession($projectID, $entranceDate, $startTime, $endTime, $comment);
		$returnArray = [];
		if($newSessionID !== false)
		{
			$returnArray['sessionId'] = $newSessionID;
			$returnArray['entranceDate'] = $entranceDate->format('Y-m-d');
			$returnArray['startTime'] = $startTime->format('H:i');
			$returnArray['endTime'] = $endTime->format('H:i');
			$returnArray['duration'] = $workDuration->format('%H:%I');
			$returnArray['comment'] = $comment;
		}
		
		echo json_encode($returnArray);
	}

	function deleteWorkSession()
	{
        if(!isSessionActive() && !isset($_GET['deleteWorkSession'])) { return; }

        $sessionID = $_GET['sessionID'];
        deleteWorkSessionFromDatabase($sessionID);
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
?>

<?php 	// Execute all ajax functions
	performAjaxRequest();
	//saveNewWorkSession();
	//deleteWorkSession();
?>
