function loadAllCharts(projectId)
{
	loadWorkSessionChart(projectId, "workSessionChart");
    loadWorkDaysRatioChart(projectId, "workDaysRatioChart");
    loadWorkTimeRatioChart(projectId, "workTimeRatioChart");
}

function loadWorkSessionChart(projectId, chartId)
{
	var chart;
	var xhttp, response;

	// ajax request
	xhttp = new XMLHttpRequest();

	// code executed after response from server
	xhttp.onreadystatechange = function()
	{
		if ((this.readyState === 4) && (this.status === 200))
		{
			response = JSON.parse(this.responseText);
			chart = getChart(projectId, chartId);

			if(response.length !== 0)
			{
				new Chart(chart.getContext('2d'), {
					type: 'horizontalBar',
					data: {
						labels: response["labels"],
						datasets: [
							{
								data: response["offsets"],
								backgroundColor: "rgba(60, 100, 130, 0)"
							},
							{
								data: response["sessionDurations"],
								backgroundColor: "#0000ff"
							}
						]
					},
					options: {
						legend: { display: false },
						title: {
							display: true,
							text: 'Übersicht zu den Work-Sessions'
						},
						scales: {
							xAxes: [{
								stacked: true,
								ticks: {
									userCallback: function(v) { return epoch_to_hh_mm(v) },
									stepSize: 60 * 60
								}
							}],
							yAxes: [{
								stacked: true
							}]
						},
						tooltips: {
							callbacks: {
								label: function(tooltipItem) {
									return epoch_to_hh_mm(tooltipItem.xLabel);
								}
							}
						}
					}
				});

				function epoch_to_hh_mm(epoch)
				{
					return new Date(epoch*1000).toISOString().substr(11, 5);
				}
			}
		}
	};

	// open and send ajax request
	xhttp.open(
		"POST",
		"ajax_charts.php?workSessionChart=1"
		+ "&projectID=" + projectId,
		true
	);
	xhttp.send();
}

function loadWorkDaysRatioChart(projectId, chartId)
{
	var chart;
	var xhttp, response;

	// ajax request
	xhttp = new XMLHttpRequest();

	// code executed after response from server
	xhttp.onreadystatechange = function()
	{
		if ((this.readyState === 4) && (this.status === 200))
		{
			response = JSON.parse(this.responseText);
			chart = getChart(projectId, chartId);

			if(response.length !== 0)
			{
				new Chart(chart, {
					type: 'doughnut',
					data: {
						labels: response["labels"],
						datasets: [
							{
								data: response["data"],
								backgroundColor: response["colors"]
							}
						]
					},
					options: {
						legend: { display: false },
						title: {
							display: true,
							text: 'Arbeitstage und freie Tage'
						}
					}
				});
			}

		}
	};


	// open and send ajax request
	xhttp.open(
		"POST",
		"ajax_charts.php?workDaysRatioChart=1"
		+ "&projectID=" + projectId,
		true
	);
	xhttp.send();
}

function loadWorkTimeRatioChart(projectId, chartId)
{
	var chart;
	var xhttp, response;

	// ajax request
	xhttp = new XMLHttpRequest();

	// code executed after response from server
	xhttp.onreadystatechange = function()
	{
		if ((this.readyState === 4) && (this.status === 200))
		{
			response = JSON.parse(this.responseText);
			chart = getChart(projectId, chartId);

			if(response.length !== 0)
			{
				new Chart(chart, {
					type: 'doughnut',
					data: {
						labels: response["labels"],
						datasets: [
							{
								data: response["sessionDurations"],
								backgroundColor: response["colors"]
							}
						]
					},
					options: {
						legend: { display: false },
						title: {
							display: true,
							text: 'Arbeitstage mit / ohne Überstunden'
						}
					}
				});
			}
		}
	};


	// open and send ajax request
	xhttp.open(
		"POST",
		"ajax_charts.php?workTimeRatioChart=1"
		+ "&projectID=" + projectId,
		true
	);
	xhttp.send();
}

function getChart(projectId, chartId)
{
    var elements, element, returnElement;

    returnElement = null;
    elements = document.getElementsByClassName(CHART_CLASS);
    for (var i = 0; i < elements.length; i++)
    {
        element = elements[i];
        if ((element.getAttribute(DATA_PROJECT_ID) === projectId.toString()) && (element.getAttribute(DATA_CHART_ID) === chartId.toString()))
        {
            returnElement = element;
            break;
        }
    }

    return returnElement;
}