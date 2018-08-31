<?php
	$userProjects = selectAllUserProjects($user_id);
?>

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
