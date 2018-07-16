function takeSessionTime()
{
	var time, hour, minute;
	var timeStartField;

	time = getCurrentTime();
	hour = time[0];
	minute = time[1];

	timeStartField = document.getElementById("input1");
	alert(hour + ":" + minute);
	timeStartField.innerHTML = hour;
}


/*
 *	##############################################
 *	Time and Clock
 *	##############################################
 */
function initialize()
{
	clock();
}

function getCurrentTime()
{
	var date, hour, minute, second;

	date = new Date();

	hour = date.getHours();
	minute = date.getMinutes();
	second = date.getSeconds();

	return [hour, minute, second];
}


/*
 *	##############################################
 *	Navigation and Tabs
 *	##############################################
 */
function clock()
{
	var time, hour, minute, second;
	var clocks, clock;

	time = getCurrentTime();
	hour = time[0];
	minute = time[1];
	second = time[2];

	if (hour < 10)
	{
		hour = "0" + hour;
	}

	if (minute < 10)
	{
		minute = "0" + minute;
	}

	if (second < 10)
	{
		second = "0" + second;
	}

	clocks = document.getElementsByClassName("clock");
	for (var i = 0; i < clocks.length; i++)
	{
		clock = clocks.item(i);
		clock.innerHTML = hour + ":" + minute + ":" + second;
	}

	// self call
	setTimeout('clock()', '500');
}

function showProject(clickedMenuItem)
{
	var projects, project, projectID;

	projectID = clickedMenuItem.getAttribute("data-projectId");
	sideMenuItems = document.getElementById("sideMenu").children;
	projects = document.getElementsByClassName("projectContent");

	for (var i = 0; i < sideMenuItems.length; i++)
	{
		sideMenuItem = sideMenuItems[i];
		if (sideMenuItem.getAttribute("data-projectId") == projectID)
		{
			sideMenuItem.className += " active";
		}
		else
		{
			sideMenuItem.className = sideMenuItem.className.replace(" active", "");
		}
	}

	for (var i = 0; i < projects.length; i++)
	{
		project = projects.item(i);
		if (project.getAttribute("data-projectId") == projectID)
		{
			project.style.display = "block";
		}
		else
		{
			project.style.display = "none";
		}
	}
}

function showTab(clickedTabItem) {
	var projectID, tabID;
	var tabs, tab;

	projectID = clickedTabItem.getAttribute("data-projectId");
	tabID = clickedTabItem.getAttribute("data-tabId");

	tabs = document.getElementsByClassName("tabContent");
	for(var i = 0; i < tabs.length; i++)
	{
		tab = tabs.item(i);
		if (tab.getAttribute("data-projectId") == projectID)
		{
			if (tab.getAttribute("data-tabId") == tabID)
			{
				tab.style.display = "block";
			}
			else
			{
				tab.style.display = "none";
			}
		}
	}
}
