function chart_test() {
    // Bar chart
    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
            labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                    data: [2478,5267,734,784,433]
                }
            ]
        },
        options: {
            legend: { display: false },
            title: {
                display: true,
                text: 'Predicted world population (millions) in 2050'
            }
        }
    });
}

function loadAllCharts(projectId)
{
    loadWorkSessionChart(projectId, "workSessionChart");
}

function loadWorkSessionChart(projectId, chartId)
{
    var chart;
    var xhttp, response;
    var barSize;

    // ajax request
    xhttp = new XMLHttpRequest();

    // code executed after response from server
    xhttp.onreadystatechange = function()
    {
        if ((this.readyState === 4) && (this.status === 200))
        {
            response = JSON.parse(this.responseText);
            chart = getChart(projectId, chartId);
            barSize = 40;

            alert(this.response);

            if(response.length !== 0)
            {
                chart.height = response['sessionDurations'].length * barSize;
                new Chart(chart, {
                    type: 'horizontalBar',
                    data: {
                        labels: response["dates"],
                        datasets: [
                            {
                                backgroundColor: "#c45850",
                                data: response["sessionDurations"]
                            }
                        ]
                    },
                    options: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Dauer der Sessions'
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    userCallback: function(v) { return epoch_to_hh_mm(v) },
                                    stepSize: 30 * 60
                                }
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

                function epoch_to_hh_mm(epoch) {
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