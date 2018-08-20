<?php

	if(isset($user_id))
	{
		try {
			$connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$allProjectsSelect = $connection->prepare("SELECT * FROM PROJECT WHERE USER_ID = :user_id ORDER BY PROJECT_ID DESC");
			$allProjectsSelect->bindValue(':user_id', $user_id);
			$allProjectsSelect->execute();
			$userProjects = $allProjectsSelect->fetchAll(PDO::FETCH_NAMED);

		
		}
		catch(PDOException $e)
		{
			print "MySQL Error: " . $e->getMessage();
		}
	}
?>

<!-- TODO: vielleicht überflüssig -->
<input type="hidden" id="selectedProject" value="0">

<div id="sideMenu">
	<?php
	foreach ($userProjects as $row => $project)
	{
		echo '<div class="noselect" data-projectId="' . $project['PROJECT_ID'] . '" onclick="showProject(this)">' . $project['PROJECT_NAME'] . '</div>';
	}
	?>
	<div class="noselect" data-projectId="X" onclick="openModal()">
		+++ neues Projekt +++
	</div>
</div>
