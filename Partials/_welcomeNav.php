<div id="welcomePage" class="projectContent" onclick="chart_test()">
	Willkommen!
	<div>
		<?php

			if (isset($_SESSION['user_id']) && isset($_SESSION['user_name']))
			{
				$user_id = $_SESSION['user_id'];
				$user_name = $_SESSION['user_name'];

				echo "User_id: $user_id";
				echo "User_name: $user_name";
			}
			else
			{
				echo "ERROR";
			}
		?>
		Starten Sie Sessions, um Ihre Arbeitszeit zu erfassen.
		Sie können die Session beliebig oft pausieren.
		Die Pausenzeiten werden addiert und von der Gesamtarbeitszeit
		abgezogen.
		Buttons:
			- Start / Stop
			- Pause / Weiter
			- Zurücksetzen
			- Speichern
	</div>
    <div>
        <canvas id="bar-chart" width="800" height="450"></canvas>
    </div>
</div>
