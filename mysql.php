<?php

	/*
	 * ========================================
	 * Basic Method
	 * ========================================
	 *
	*/

	function createDatabaseConnection()
	{
		$servername = "localhost";
		$database = "aze";
		$username = "aze_adm";
		$password = "*+Pa\$\$w0rD69+*";
		
		$conn = null;		
		try
		{
			$conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
			print "Fehler bei der Verbindung zur Datenbank: " . $e->getMessage();
		}

		return $conn;

	}

	/*
	 * ========================================
	 * Project Methods
	 * ========================================
	 *
	*/

	function insertNewProject($user_id, $projectName, $dateStart, $dateEnd, $income, $incomeType, $desiredDaylyWorktime, $desiredHourlyWage)
	{
		try
		{
            $conn = createDatabaseConnection();
            $query = "INSERT INTO PROJECT (USER_ID, PROJECT_NAME, DATE_START, DATE_END, DESIRED_DAYLY_WORKTIME, DESIRED_HOURLY_WAGE, INCOME, INCOME_TYPE) VALUES (:user_id, :projectName, :dateStart, :dateEnd, :desiredDaylyWorktime, :desiredHourlyWage, :income, :incomeType)";
            $statement = $conn->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':projectName', $projectName);

            if(!$dateStart)
            {
                $statement->bindValue(':dateStart', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':dateStart', $dateStart->format('Y-m-d'));
            }

            if(!$dateEnd)
            {
                $statement->bindValue(':dateEnd', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':dateEnd', $dateEnd->format('Y-m-d'));
            }

            if($desiredDaylyWorktime === "")
            {
                $statement->bindValue(':desiredDaylyWorktime', null, PDO::PARAM_NULL);
            }
            else
			{
                $statement->bindValue(':desiredDaylyWorktime', $desiredDaylyWorktime);
			}

            if($desiredHourlyWage === "")
            {
                $statement->bindValue(':desiredHourlyWage', null, PDO::PARAM_NULL);
            }
            else
			{
                $statement->bindValue(':desiredHourlyWage', $desiredHourlyWage);
			}

            if($income === "")
            {
                $statement->bindValue(':income', null, PDO::PARAM_NULL);
            }
            else
			{
                $statement->bindValue(':income', $income);
			}

            if($incomeType === "")
            {
                $statement->bindValue(':incomeType', null, PDO::PARAM_NULL);
            }
            else
			{
                $statement->bindValue(':incomeType', $incomeType);
			}

            $statement->execute();
        }
        catch(PDOException $e)
		{
			print "Fehler beim Schreiben des Projekts: " . $e->getMessage();
		}
	}

	function selectAllUserProjects($user_id)
	{
		$userProjects = false;
		try 
		{
			$conn = createDatabaseConnection();

			$query = "SELECT * FROM PROJECT WHERE USER_ID = :user_id ORDER BY PROJECT_ID DESC";
			$statement = $conn->prepare($query);
			$statement->bindValue(':user_id', (string) $user_id);
			$statement->execute();
			$userProjects = $statement->fetchAll(PDO::FETCH_NAMED);

            foreach($userProjects as $key => $project)
            {
                if (isset($userProjects[$key]['DESIRED_DAYLY_WORKTIME']))
                {
                    $userProjects[$key]['DESIRED_DAYLY_WORKTIME'] = DateTime::createFromFormat(
                        'H:i:s', $userProjects[$key]['DESIRED_DAYLY_WORKTIME']
                    )->format('H:i');
                }
            }
		}
		catch(PDOException $e)
		{
			print "Fehler beim Laden der Projekte: " . $e->getMessage();
		}
		return $userProjects;
	}

	function selectProjectData($projectId)
    {
        $projectData = false;
        try
        {
            $conn = createDatabaseConnection();

            $query = "SELECT * FROM PROJECT WHERE PROJECT_ID=:projectId";
            $statement = $conn->prepare($query);
            $statement->bindValue(':projectId', (string) $projectId);
            $statement->execute();
            $projectData = $statement->fetch(PDO::FETCH_NAMED);

            if ($projectData['DESIRED_DAYLY_WORKTIME'] !== null)
            {
                $projectData['DESIRED_DAYLY_WORKTIME'] = DateTime::createFromFormat(
                    'H:i:s', $projectData['DESIRED_DAYLY_WORKTIME']
                )->format('H:i');
            }
        }
        catch (PDOException $e)
        {
            print "Fehler beim Laden der Projektdaten: " . $e->getMessage();
        }

        return $projectData;
    }

	function updateProject($projectID, $user_id, $projectName, $dateStart, $dateEnd, $income, $incomeType, $desiredDaylyWorktime, $desiredHourlyWage)
	{
		try
		{
			$conn = createDatabaseConnection();

			$query = "UPDATE PROJECT SET PROJECT_NAME=:projectName, DATE_START=:dateStart, DATE_END=:dateEnd, INCOME=:income, INCOME_TYPE=:incomeType, DESIRED_DAYLY_WORKTIME=:desiredDaylyWorktime, DESIRED_HOURLY_WAGE=:desiredHourlyWage WHERE PROJECT_ID=:projectID";
			$statement = $conn->prepare($query);
			$statement->bindValue(':projectID', (string) $projectID);
            $statement->bindValue(':projectName', $projectName);

            if(!$dateStart)
            {
                $statement->bindValue(':dateStart', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':dateStart', $dateStart->format('Y-m-d'));
            }

            if(!$dateEnd)
            {
                $statement->bindValue(':dateEnd', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':dateEnd', $dateEnd->format('Y-m-d'));
            }

            if($desiredDaylyWorktime === "")
            {
                $statement->bindValue(':desiredDaylyWorktime', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':desiredDaylyWorktime', $desiredDaylyWorktime);
            }

            if($desiredHourlyWage === "")
            {
                $statement->bindValue(':desiredHourlyWage', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':desiredHourlyWage', $desiredHourlyWage);
            }

            if($income === "")
            {
                $statement->bindValue(':income', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':income', $income);
            }

            if($incomeType === "")
            {
                $statement->bindValue(':incomeType', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':incomeType', $incomeType);
            }

            $statement->execute();
            header('Location: /main.php');

		}
		catch (PDOException $e)
		{
			print "Fehler beim Bearbeiten des Projektes: " . $e->getMessage();
		}
	}

	function deleteProject($projectID)
	{
		try
		{
			$conn = createDatabaseConnection();

			$projectQuery = "DELETE FROM PROJECT WHERE PROJECT_ID=:projectID";
			$sessionQuery = "DELETE FROM WORK_SESSION WHERE PROJECT_ID=:projectID";

			$projectStatement = $conn->prepare($projectQuery);
			$projectStatement->bindValue(':projectID', (string) $projectID);

			$sessionStatement = $conn->prepare($sessionQuery);
			$sessionStatement->bindValue(':projectID', (string) $projectID);

			$projectStatement->execute();
			$sessionStatement->execute();

            header('Location: /main.php');
		}
		catch (PDOException $e)
		{
			print "Fehler beim Löschen des Projektes: " . $e->getMessage();
		}
	}


	/*
	 * ========================================
	 * Session Methods
	 * ========================================
	 *
	*/
	function insertNewWorkSession($projectID, $entranceDate, $startTime, $endTime, $comment)
	{
		$newSessionID = false;
		try
		{
			$conn = createDatabaseConnection();
			$query = "INSERT INTO WORK_SESSION (PROJECT_ID, DATE, TIME_FROM, TIME_TO, COMMENT) VALUES (:projectID, :entranceDate, :startTime, :endTime, :comment)";
			$statement= $conn->prepare($query);
			$statement->bindValue(':projectID', (string) $projectID);
			$statement->bindValue(':entranceDate', $entranceDate);
			$statement->bindValue(':startTime', $startTime);
			$statement->bindValue(':endTime', $endTime);
			if($comment === "")
			{
				$statement->bindValue(':comment', null, PDO::PARAM_NULL);
			}
			else
			{
				$statement->bindValue(':comment', $comment);
			}
			
			$statement->execute();
			$newSessionID = $conn->lastInsertId();
		}
		catch (PDOException $e)
		{
			print "MySQL Error: " .$e->getMessage();
		}

		return $newSessionID;
			
	}


	function selectAllWorkSessions($projectID)
	{
		$projectSessions = false;
		try
		{
			$conn = createDatabaseConnection();
			$query = "SELECT * FROM WORK_SESSION WHERE PROJECT_ID=:projectID ORDER BY SESSION_ID DESC";
			$statement = $conn->prepare($query);
			$statement->bindValue(':projectID', (string) $projectID);
			$statement->execute();

			$projectSessions = $statement->fetchAll(PDO::FETCH_NAMED);
			foreach($projectSessions as $key => $projectSession)
			{
				$projectSessions[$key]['TIME_FROM'] = DateTime::createFromFormat(
					'H:i:s', $projectSession['TIME_FROM']
				)->format('H:i');
				$projectSessions[$key]['TIME_TO'] = DateTime::createFromFormat(
					'H:i:s', $projectSession['TIME_TO']
				)->format('H:i');
			}
		}
		catch(PDOException $e)
		{
			print "Fehler beim Laden der Sessions: " . $e->getMessage();
		}

		return $projectSessions;
	}

	function deleteWorkSessionFromDatabase($sessionID)
	{
		$sessionDeleted = false;
		try
		{
			$conn = createDatabaseConnection();
			$query = "DELETE FROM WORK_SESSION WHERE SESSION_ID=:sessionID";
			$statement = $conn->prepare($query);
			$statement->bindValue('sessionID', (string) $sessionID);
			$statement->execute();
			$sessionDeleted = true;
		}
		catch (PDOException $e)
		{
			print "Fehler beim Löschen der Session: " . $e->getMessage();
		}

		return $sessionDeleted;
	}

	function updateWorkSessionInDatabase($sessionID, $startTime, $endTime, $comment)
    {
    	$workSessionUpdated = false;
        try
        {
            $conn = createDatabaseConnection();
            $query = "UPDATE WORK_SESSION SET TIME_FROM=:startTime, TIME_TO=:endTime, COMMENT=:comment WHERE SESSION_ID=:sessionID";

            $statement = $conn->prepare($query);
            $statement->bindValue(':sessionID', (string) $sessionID);
            $statement->bindValue(':startTime', $startTime);
            $statement->bindValue(':endTime', $endTime);
            if($comment === "")
            {
                $statement->bindValue(':comment', null, PDO::PARAM_NULL);
            }
            else
            {
                $statement->bindValue(':comment', $comment);
            }

            $statement->execute();
            $workSessionUpdated = true;
        }
        catch (PDOException $e)
        {
            print "Fehler beim Bearbeiten der Session: " . $e->getMessage();
        }
        return $workSessionUpdated;
    }
?>