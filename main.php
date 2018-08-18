<?php
	session_start();

	$user_id = $_SESSION['user_id'];
	$user_name = $_SESSION['user_name'];

	$servername = "localhost";
	$database = "aze";
	$username = "aze_adm";
	$password = "*+Pa\$\$w0rD69+*";
	$conn = null;
	
	if(!isset($user_id) && !isset($user_name))
	{
		header('Location: /login.php');
	}

	if(isset($_GET['logout']))
	{
		header('Location: /logout.php');
	}

	if(isset($_GET['newProject']))
	{
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$projectName = $_POST['projectName'];
			$dateStart = date("Y-m-d", $_POST['dateStart']);
			$dateEnd = date("Y-m-d", $_POST['dateEnd']);
			$income = $_POST['income'];
			$incomeType = $_POST['incomeType'];
			$desiredDaylyWorktime = $_POST['desiredDaylyWorktime'];
			$desiredHourlyWage = $_POST['desiredHourlyWage'];

			$statement = $conn->prepare("INSERT INTO PROJECT (USER_ID, PROJECT_NAME, DATE_START, DATE_END, DESIRED_DAYLY_WORKTIME, DESIRED_HOURLY_WAGE, INCOME, INCOME_TYPE) VALUES (:user_id, :projectName, :dateStart, :dateEnd, :desiredDaylyWorktime, :desiredHourlyWage, :income, :incomeType)");
			$statement->bindValue(':user_id', $user_id);
			$statement->bindValue(':projectName', $projectName);
			$statement->bindValue(':dateStart', $dateStart);
			$statement->bindValue(':dateEnd', $dateEnd);
			$statement->bindValue(':desiredDaylyWorktime', $desiredDaylyWorktime);
			$statement->bindValue(':desiredHourlyWage', $desiredHourlyWage);
			$statement->bindValue(':income', $income);
			$statement->bindValue(':incomeType', $incomeType);

			if(!$dateStart)
			{
				$statement->bindValue(':dateStart', null, PDO::PARAM_NULL);
			}
			
			if(!$dateEnd)
			{
				$statement->bindValue(':dateEnd', null, PDO::PARAM_NULL);
			}
			
			if($desiredDaylyWorktime === "")
			{
				$statement->bindValue(':desiredDaylyWorktime', null, PDO::PARAM_NULL);
			}
			
			if($desiredHourlyWage === "")
			{
				$statement->bindValue(':desiredHourlyWage', null, PDO::PARAM_NULL);
			}
			
			if($income === "")
			{
				$statement->bindValue(':income', null, PDO::PARAM_NULL);
			}
			
			if($incomeType === "")
			{
				$statement->bindValue(':incomeType', null, PDO::PARAM_NULL);
			}

			$statement->execute();
		}
		catch(PDOException $e)
		{
			print "MySQL Error: " . $e->getMessage();
		}
	}

?>

<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<title> Arbeitszeiterfassung </title>

	<link rel="stylesheet" type="text/css" href="layout.css">
	<link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="side_menu.css">

	<script src="main.js"></script>
</head>

<body onload="initialize()">
	<main>

		<!-- Main Wrapper with grid layout -->
		<div id="mainWrapper">
			
			<header>
				<?php
					include 'Partials/_header.php';
				?>
			</header>

			<nav>
				<?php
					include 'Partials/_projects.php';
				?>
			</nav>

			<aside>
				<?php
					include 'Partials/_welcomeNav.php';
					include 'Partials/_projectContents.php';
				?>
			</aside>
		</div>
		
		<div id="myModal" class="modal">
			<div class="modalContent">
				<form action="?newProject=1" method="post">
					<h2>
						Neues Projekt erstellen
					</h2>
					<div>
						Name des Projektes: <input type="text" name="projectName" required="required">
					</div>
					<div>
						Start des Projektes: <input type="date" name="dateStart">
					</div>
					<div>
						Ende des Projektes: <input type="date" name="dateEnd">
					</div>
					<div>
						Einkommen: <input type="number" min="0.00" step="0.01" name="income">
					</div>
					<div>
						Art des Einkommens: <input type="text" name="incomeType">
					</div>
					<div>
						Tägliche Wunscharbeitszeit: <input type="time" name="desiredDaylyWorktime">
					</div>
					<div>
						Stündliches Wunscheinkommen: <input type="number" min="0.00" step="0.01" name="desiredHourlyWage">
					</div>
					<div>
						<input type="reset" value="Abbrechen" onclick="closeModal()">
						<input type="submit" value="Projekt anlegen">
					</div>
				</form>
			</div>
		</div>

	</main>
</body>

</html>
