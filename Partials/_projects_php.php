<?php
	$userProjects = selectAllUserProjects($user_id);
?>

<!-- TODO: vielleicht überflüssig -->
<input type="hidden" id="selectedProject" value="0">

<div id="sideMenu">
	<?php
	if ($userProjects !== false)
	{
		foreach ($userProjects as $row => $project)
		{
	?>
			<div 
				class="noselect" 
				data-projectId="<?php echo $project['PROJECT_ID']; ?>" 
				onclick="showProject(this)"
			>
				<?php echo $project['PROJECT_NAME']; ?>
			</div>
	<?php
		}
	}
	?>
</div>
