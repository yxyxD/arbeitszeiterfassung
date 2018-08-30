<?php
	/* 
	 * #######################
	 * Session handling
	 * ####################### 
	 */
	session_start();
	
	$user_id = $_SESSION['user_id'];
	$user_name = $_SESSION['user_name'];
	
	
	/* 
	 * #######################
	 * PHP includes
	 * #######################
	 */
	 include 'mysql.php';
     include 'helper.php';
	
	/* 
	 * #######################
	 * Handling form submits
	 * #######################
	 */
	// handle session timeout
	if(!isset($user_id) && !isset($user_name))
	{
		header('Location: /login.php');
	}

	// handle logout
	if(isset($_GET['logout']))
	{
		header('Location: /logout.php');
	}

	// handle creating a new project
	if(isset($_GET['newProject']))
	{
        $projectName = $_POST['projectName'];
        $dateStart = DateTime::createFromFormat('Y-m-d', $_POST['dateStart']);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $_POST['dateEnd']);
        $income = $_POST['income'];
        $incomeType = $_POST['incomeType'];
        $desiredDaylyWorktime = $_POST['desiredDaylyWorktime'];
        $desiredHourlyWage = $_POST['desiredHourlyWage'];

		insertNewProject($user_id, $projectName, $dateStart, $dateEnd, $income, $incomeType, $desiredDaylyWorktime, $desiredHourlyWage);
	}
?>

<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<title> Arbeitszeiterfassung </title>

	<link rel="stylesheet" type="text/css" href="layout.css">
	<link rel="stylesheet" type="text/css" href="main.css">

    <script src="Chart.bundle.min.js"></script>
	<script src="main.js"></script>
    <script src="charts.js"></script>


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
				<button onclick="openModal()">
				+++ neues Projekt +++
				</button>
				<?php
					include 'Partials/_projects_php.php';
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
