/*
 *	##############################################
 *	Global variables
 *	##############################################
 */
/* #### class names #### */
var CLOCK = "clock";
var NEW_SESSION_TIME_START = "newSessionTimeStart";
var NEW_SESSION_TIME_END = "newSessionTimeEnd";
var NEW_SESSION_COMMENT = "newSessionComment";
var SESSION_TABLE = "workSessionTable";
var CHART_CLASS = "chart";

/* #### data tags #### */
var DATA_PROJECT_ID = "data-projectId";
var DATA_TAB_ID = "data-tabId";
var DATA_SESSION_ID = "data-sessionId";
var DATA_CHART_ID = "data-chartId";


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
function getElementByClassNameAndId(className, id)
{
	var elements, element, returnElement;

	returnElement = null;
	elements = document.getElementsByClassName(className);
	for (var i = 0; i < elements.length; i++)
	{
		element = elements[i];
		if (element.getAttribute(DATA_PROJECT_ID) === id)
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
 *	Functions for session creator
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
	startTimeField = getElementByClassNameAndId(NEW_SESSION_TIME_START, projectID);
	endTimeField = getElementByClassNameAndId(NEW_SESSION_TIME_END, projectID);
	
	if(clickedButton.value === "Starte Session")
	{
		startTimeField.value = hour + ":" + minute;
		endTimeField.value = "";
		
		clickedButton.value = "Beende Session";
	}
	else
	{	
		endTimeField.value = hour + ":" + minute;
		
		clickedButton.value = "Starte Session";
	}
}

function saveNewWorkSession(clickedButton)
{
	var projectID;
	var startTime, endTime, comment;
	var xhttp, response;
	var sessionTable, newRow, cell1, cell2, cell3, cell4, cell5, cell6;
	var input1, input2, input3, textarea, button1, button2;
	
	projectID = clickedButton.getAttribute(DATA_PROJECT_ID);
	startTime = getElementByClassNameAndId(NEW_SESSION_TIME_START, projectID).value;
	endTime = getElementByClassNameAndId(NEW_SESSION_TIME_END, projectID).value;
	comment = getElementByClassNameAndId(NEW_SESSION_COMMENT, projectID).value;
	
	// ajax request
	xhttp = new XMLHttpRequest();
	
	// code executed after response from server
	xhttp.onreadystatechange = function()
	{
		if ((this.readyState === 4) && (this.status === 200))
		{
			response = JSON.parse(this.responseText);
			if(response.length !== 0)
			{
				sessionTable = getElementByClassNameAndId(SESSION_TABLE, projectID);
				newRow = sessionTable.insertRow(1);
				cell1 = newRow.insertCell(0);
				cell2 = newRow.insertCell(1);
				cell3 = newRow.insertCell(2);
				cell4 = newRow.insertCell(3);
				cell5 = newRow.insertCell(4);
				cell6 = newRow.insertCell(5);

				input1 = document.createElement("input");
				input1.type = "time";
				input1.name = "timeStart";
				input1.value = response.startTime;
				input1.classList.add("timeSelect");
				input1.dataset.sessionid = response.sessionId;

				input2 = document.createElement("input");
				input2.type = "time";
				input2.name = "timeEnd";
				input2.value = response.endTime;
				input2.classList.add("timeSelect");
				input2.dataset.sessionid = response.sessionId;

				input3 = document.createElement("input");
				input3.type = "time";
				input3.name = "timeDiff";
				input3.value = response.duration;
				input3.classList.add("timeSelect");
				input3.dataset.sessionid = response.sessionId;
				input3.readOnly = true;

				textarea = document.createElement("textarea");
				textarea.maxLength = 4000;
				textarea.rows = 10;
				textarea.cols = 25;
				textarea.innerHTML = response.comment;
				textarea.classList.add("sessionComment");
				textarea.dataset.sessionid = response.sessionId;

				button1 = document.createElement("input");
				button1.type = "button";
				button1.value = "Änderungen speichern";
				//button1.classList.add("");
				button1.dataset.sessionid = response.sessionId;
				button1.onclick = function () {updateWorkSession(button1)};

				button2 = document.createElement("input");
				button2.type = "button";
				button2.value = "Löschen";
				//button2.classList.add("");
				button2.dataset.sessionid = response.sessionId;
				button2.onclick = function () {deleteWorkSession(this) };


				cell1.appendChild(input1);
				cell2.appendChild(input2);
				cell3.appendChild(input3);
				cell4.appendChild(textarea);
				cell5.appendChild(button1);
				cell6.appendChild(button2);
			}
		}
	};
	
	// open and send ajax request
	xhttp.open(
		"POST",
		"ajax.php?newWorkSession=1"
			+ "&projectID=" + projectID
			+ "&startTime=" + startTime
			+ "&endTime=" + endTime
			+ "&comment=" +encodeURIComponent(comment),
		true
	);
	xhttp.send();
}

function deleteWorkSession(clickedButton)
{
    var sessionID;
    var xhttp;
    var sessionTable, tableRowIndex;

    sessionID = clickedButton.getAttribute(DATA_SESSION_ID);
	sessionTable = clickedButton.parentNode.parentNode.parentNode;

    tableRowIndex = clickedButton.parentNode.parentNode.rowIndex;

    // ajax request
    xhttp = new XMLHttpRequest();

    // code executed after response from server
    xhttp.onreadystatechange = function()
	{
        if ((this.readyState === 4) && (this.status === 200)) {
            sessionTable.deleteRow(tableRowIndex);
        }
	};

    //open and send ajax request
    xhttp.open(
        "POST",
        "ajax.php?deleteWorkSession=1"
        + "&sessionID=" + sessionID,
        true
    );

    xhttp.send();
}

function updateWorkSession(clickedButton)
{
    var sessionID;
    var xhttp, response;
    var tableCells;
    var startTime, endTime, comment;
    var input3;

    sessionID = clickedButton.getAttribute(DATA_SESSION_ID);
    // get the <td> elements
	tableCells = clickedButton.parentElement.parentElement.children;
	input3 = tableCells[2].firstElementChild;

	startTime = tableCells[0].firstElementChild.value;
	endTime = tableCells[1].firstElementChild.value;
	comment = tableCells[3].firstElementChild.value;

	xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function()
    {
        if ((this.readyState === 4) && (this.status === 200)) {
            response = JSON.parse(this.responseText);
            if(response.length !== 0) {
                input3.value = response.duration;
            }
        }
    };

    //open and send ajax request
    xhttp.open(
        "POST",
        "ajax.php?updateWorkSession=1"
        + "&sessionID=" + sessionID
        + "&startTime=" + startTime
		+ "&endTime=" + endTime
		+ "&comment=" + comment,
        true
    );

    xhttp.send();
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
	var sideMenuItems, sideMenuItem;

	projectID = clickedMenuItem.getAttribute(DATA_PROJECT_ID);
	sideMenuItems = document.getElementById("sideMenu").children;
	projects = document.getElementsByClassName("projectContent");

	for (var i = 0; i < sideMenuItems.length; i++)
	{
		sideMenuItem = sideMenuItems[i];

        sideMenuItem.className = sideMenuItem.className.replace(" active", "");
		if (sideMenuItem.getAttribute(DATA_PROJECT_ID) === projectID)
		{
			sideMenuItem.className += " active";
		}
	}

	for (var i = 0; i < projects.length; i++)
	{
		project = projects.item(i);

        project.style.display = "none";
		if (project.getAttribute(DATA_PROJECT_ID) === projectID)
		{
			project.style.display = "block";
		}
	}
}

function showTab(clickedTabItem)
{
	var projectID, tabID;
	var tabs, tab;

	projectID = clickedTabItem.getAttribute(DATA_PROJECT_ID);
	tabID = clickedTabItem.getAttribute("data-tabId");

	tabs = document.getElementsByClassName("tabContent");
	for(var i = 0; i < tabs.length; i++)
	{
		tab = tabs.item(i);
		if (tab.getAttribute(DATA_PROJECT_ID) === projectID)
		{
			if (tab.getAttribute(DATA_TAB_ID) === tabID)
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


/*
 *	##############################################
 *	Modal
 *	##############################################
 */
function openModal()
{
	var modal = document.getElementById('myModal');
	
	modal.style.display = "block";
}

function closeModal()
{
	var modal = document.getElementById('myModal');
	
	modal.style.display = "none";
}








