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
	var sessionTable, newRow, cell1, cell2, cell3, cell4, cell5, cell6, cell7;
	var input1, input2, input3, textarea, div1, div2, img1, img2;
	
	projectID = clickedButton.getAttribute(DATA_PROJECT_ID);
	startTime = getElementByClassNameAndId(NEW_SESSION_TIME_START, projectID);
	endTime = getElementByClassNameAndId(NEW_SESSION_TIME_END, projectID);
	comment = getElementByClassNameAndId(NEW_SESSION_COMMENT, projectID);
	
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
				newRow = sessionTable.insertRow(-1);
				cell1 = newRow.insertCell(0);
				cell2 = newRow.insertCell(1);
				cell3 = newRow.insertCell(2);
				cell4 = newRow.insertCell(3);
				cell5 = newRow.insertCell(4);
				cell6 = newRow.insertCell(5);
				cell7 = newRow.insertCell(6);


				cell1.innerHTML = "neu";

				input1 = document.createElement("input");
				input1.type = "time";
				input1.name = "timeStart";
				input1.value = response.startTime;
				input1.classList.add("stdInput");
				input1.dataset.sessionid = response.sessionId;

				input2 = document.createElement("input");
				input2.type = "time";
				input2.name = "timeEnd";
				input2.value = response.endTime;
				input2.classList.add("stdInput");
				input2.dataset.sessionid = response.sessionId;

				input3 = document.createElement("input");
				input3.type = "time";
				input3.name = "timeDiff";
				input3.value = response.duration;
				input3.classList.add("stdInput");
				input3.dataset.sessionid = response.sessionId;
				input3.readOnly = true;

				textarea = document.createElement("textarea");
				textarea.maxLength = 4000;
				textarea.cols = 25;
				textarea.innerHTML = response.comment;
				textarea.classList.add("sessionComment");
				textarea.dataset.sessionid = response.sessionId;

				div1 = document.createElement("div");
				div1.dataset.sessionid = response.sessionId;
				div1.onclick = function () {updateWorkSession(this)};

				img1 = document.createElement("img");
				img1.src = "images/edit-solid.png";
				img1.height = "20";
				img1.width = "20";
				div1.appendChild(img1);

				div2 = document.createElement("div");
				div2.dataset.sessionid = response.sessionId;
				div2.onclick = function () {deleteWorkSession(this) };

                img2 = document.createElement("img");
                img2.src = "images/trash-solid.png";
                img2.height = "20";
                img2.width = "20";
                div2.appendChild(img2);

				cell2.appendChild(input1);
				cell3.appendChild(input2);
				cell4.appendChild(input3);
				cell5.appendChild(textarea);
				cell6.appendChild(div1);
				cell7.appendChild(div2);

				startTime.value = 0;
				endTime.value = 0;
				comment.value = comment.defaultValue;
				alert("Session erfolgreich angelegt");
			}
		}
	};
	
	// open and send ajax request
	xhttp.open(
		"POST",
		"ajax.php?newWorkSession=1"
			+ "&projectID=" + projectID
			+ "&startTime=" + startTime.value
			+ "&endTime=" + endTime.value
			+ "&comment=" +encodeURIComponent(comment.value),
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
            alert("Session erfolgreich gelöscht");
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
	input3 = tableCells[3].firstElementChild;

	startTime = tableCells[1].firstElementChild.value;
	endTime = tableCells[2].firstElementChild.value;
	comment = tableCells[4].firstElementChild.value;

	xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function()
    {
        if ((this.readyState === 4) && (this.status === 200)) {
            response = JSON.parse(this.responseText);
            if(response.length !== 0) {
                input3.value = response.duration;
                alert("Änderungen gespeichert");
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








