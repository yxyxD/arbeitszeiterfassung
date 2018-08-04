/*
 *	##############################################
 *	Global variables
 *	##############################################
 */
/* #### class names #### */
var CLOCK = "clock";
var NEW_SESSION_TIME_START = "newSessionTimeStart";
var NEW_SESSION_TIME_END = "newSessionTimeEnd";

/* #### data tags #### */
var DATA_PROJECT_ID = "data-projectId";
var DATA_TAB_ID = "data-tabId";


/*
 *	##############################################
 *	Initialize
 *	##############################################
 */
function initialize()
{
	clock();
}


/*
 *	##############################################
 *	Additional functions
 *	##############################################
 */
function getClassElementForProject(className, projectID)
{
	var elements, element, returnElement;

	returnElement = null;
	elements = document.getElementsByClassName(className);
	for (var i = 0; i < elements.length; i++)
	{
		element = elements[i];
		if (element.getAttribute(DATA_PROJECT_ID) == projectID)
		{
			returnElement = element;
			break;
		}
	}

	return returnElement;
}

function getIdOfSelectedProject()
{
	return document.getElementById("selectedProject").value;
}

function setIdOfSelectedProject(newID)
{
	document.getElementById("selectedProject").value = newID;
}


/*
 *	##############################################
 *	functions for session creator
 *	##############################################
 */
function takeSessionTime(clickedButton)
{
	var time, hour, minute;
	var projectID;
	var startTimeField, endTimeField;

	time = getCurrentTime();
	hour = time[0];
	minute = time[1];

	projectID = clickedButton.getAttribute(DATA_PROJECT_ID);
	startTimeField = getClassElementForProject(NEW_SESSION_TIME_START, projectID);
	startTimeField.value = hour + ":" + minute;
}


/*
 *	##############################################
 *	Time and Clock
 *	##############################################
 */
function getCurrentTime()
{
	var date, hour, minute, second;

	date = new Date();

	hour = date.getHours();
	if (hour < 10) { hour = "0" + hour; }

	minute = date.getMinutes();
	if (minute < 10) { minute = "0" + minute; }

	second = date.getSeconds();
	if (second < 10) { second = "0" + second; }

	return [hour, minute, second];
}

function clock()
{
	var time, hour, minute, second;
	var clocks, clock;

	time = getCurrentTime();
	hour = time[0];
	minute = time[1];
	second = time[2];

	clocks = document.getElementsByClassName("clock");
	for (var i = 0; i < clocks.length; i++)
	{
		clock = clocks.item(i);
		clock.innerHTML = hour + ":" + minute + ":" + second;
	}

	// self call
	setTimeout('clock()', '500');
}


/*
 *	##############################################
 *	Navigation and Tabs
 *	##############################################
 */
function showProject(clickedMenuItem)
{
	var projects, project, projectID;

	projectID = clickedMenuItem.getAttribute(DATA_PROJECT_ID);
	sideMenuItems = document.getElementById("sideMenu").children;
	projects = document.getElementsByClassName("projectContent");

	for (var i = 0; i < sideMenuItems.length; i++)
	{
		sideMenuItem = sideMenuItems[i];
		if (sideMenuItem.getAttribute(DATA_PROJECT_ID) == projectID)
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
		if (project.getAttribute(DATA_PROJECT_ID) == projectID)
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

	projectID = clickedTabItem.getAttribute(DATA_PROJECT_ID);
	tabID = clickedTabItem.getAttribute("data-tabId");

	tabs = document.getElementsByClassName("tabContent");
	for(var i = 0; i < tabs.length; i++)
	{
		tab = tabs.item(i);
		if (tab.getAttribute(DATA_PROJECT_ID) == projectID)
		{
			if (tab.getAttribute(DATA_TAB_ID) == tabID)
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
