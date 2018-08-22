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
		}
		catch(PDOException $e)
		{
			print "Fehler beim Laden der Projekte: " . $e->getMessage();
		}
		return $userProjects;
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
			$statement->bindValue(':entranceDate', $entranceDate->format('Y-m-d'));
			$statement->bindValue(':startTime', $startTime->format('H:i'));
			$statement->bindValue(':endTime', $endTime->format('H:i'));
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
		}
		catch(PDOException $e)
		{
			print "Fehler beim Laden der Sessions: " . $e->getMessage();
		}
		return $projectSessions;
	}

?>