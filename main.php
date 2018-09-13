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
	 * Handling session errors
	 * #######################
	 */
	// handle session timeout
	if(!isset($user_id) && !isset($user_name))
	{
		header('Location: /login.php');
	}

    /*
     * #######################
     * Handling form submits
     * #######################
     */
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

		header('Location: /main.php');
	}

	// handle updating a project
    if(isset($_GET['updateProject']))
    {
        $projectID = $_GET['updateProject'];
        $projectName = $_POST['projectName'];
        $dateStart = DateTime::createFromFormat('Y-m-d', $_POST['dateStart']);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $_POST['dateEnd']);
        $income = $_POST['income'];
        $incomeType = $_POST['incomeType'];
        $desiredDaylyWorktime = $_POST['desiredDaylyWorktime'];
        $desiredHourlyWage = $_POST['desiredHourlyWage'];

        updateProject($projectID, $user_id, $projectName, $dateStart, $dateEnd, $income, $incomeType, $desiredDaylyWorktime, $desiredHourlyWage);

		header('Location: /main.php');
    }

    // handle deleting a project
    if(isset($_GET['deleteProject']))
    {
        $projectID = $_GET['deleteProject'];
        deleteProject($projectID);

		header('Location: /main.php');
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
                <div id="newProjectButton" onclick="openModal()">
                    Neues Projekt
				</div>
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
                    <table class="stdTable">
                        <tr>
                            <td colspan="2">
                                <h2>
                                    Neues Projekt erstellen
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td> Name des Projektes: </td>
                            <td> <input type="text" name="projectName" required="required" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td> Start des Projektes: </td>
                            <td> <input type="date" name="dateStart" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td> Ende des Projektes: </td>
                            <td> <input type="date" name="dateEnd" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td>  Einkommen: </td>
                            <td> <input type="number" min="0.00" step="0.01" name="income" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td> Art des Einkommens: </td>
                            <td> <input type="text" name="incomeType" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td> Tägliche Wunscharbeitszeit: </td>
                            <td> <input type="time" name="desiredDaylyWorktime" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td> Stündliches Wunscheinkommen: </td>
                            <td> <input type="number" min="0.00" step="0.01" name="desiredHourlyWage" class="stdInput"> </td>
                        </tr>
                        <tr>
                            <td> <input type="reset" value="Abbrechen" class="stdButton" onclick="closeModal()"> </td>
                            <td> <input type="submit" value="Projekt anlegen" class="stdButton"> </td>
                        </tr>
                    </table>
				</form>
			</div>
		</div>

	</main>
</body>

</html>
