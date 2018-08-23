<?php

    if(isset($_GET['updateProject']))
    {
        $projectID = $_GET['updateProject'];
        $projectName = $_POST['projectName'];
        $dateStart = DateTime::createFromFormat('Y-m-d', $_POST['dateStart']);
        $dateEnd = DateTime::createFromFormat('Y-m-d', $_POST['dateEnd']);
        $income = $_POST['income'];
        $incomeType = $_POST['incomeType'];
        $desiredDaylyWorktime = $_POST['desiredDaylyWorktime'];
        $desiredHourlyWage = $_POST['desiredHourlyWage'];

        updateProject($projectID, $user_id, $projectName, $dateStart, $dateEnd, $income, $incomeType, $desiredDaylyWorktime, $desiredHourlyWage);
    }

    if(isset($_GET['deleteProject']))
    {
        $projectID = $_GET['deleteProject'];
        deleteProject($projectID);
    }

?>

<?php
if($userProjects !== false)
{
    foreach ($userProjects as $row => $project)
    {
        ?>
        <div class="projectContent" data-projectId="<?= $project['PROJECT_ID'] ?>">
            <div id="tabMenu">
                <div class="tabMenuItem noselect" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="1"
                     onclick="showTab(this)">
                    Neue Session
                </div>
                <div class="tabMenuItem noselect" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="2"
                     onclick="showTab(this)">
                    Sessions verwalten
                </div>
                <div class="tabMenuItem noselect" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="3"
                     onclick="showTab(this)">
                    Ergebnisse
                </div>
                <div class="tabMenuItem noselect" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="4"
                     onclick="showTab(this)">
                    Projekteinstellungen
                </div>
            </div>

            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="1">
                <div class="centering">
                    <div class="clock"></div>
                </div>
                <div>
                    Beginn der Session:
                    <input
                            type="time"
                            name="timeStart"
                            class="timeSelect newSessionTimeStart"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                    >
                </div>
                <div>
                    Ende der Session:
                    <input
                            type="time"
                            name="timeEnd"
                            class="timeSelect newSessionTimeEnd"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                    >
                </div>
                <div>
                    <input
                            type="button"
                            value="Starte Session"
                            class="stdButton"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                            onclick="takeSessionTime(this)"
                    >
                </div>
                <div>
				<textarea
                        maxlength="4000"
                        rows="10"
                        cols="100"
                        class="newSessionComment"
                        data-projectId="<?= $project['PROJECT_ID'] ?>"
                        placeholder="--- Anmerkungen (maximal 4000 Zeichen) ---"
                ></textarea>
                </div>
                <div>
                    <!--<input type="reset" value="Zurücksetzen" class="stdButton">-->
                    <input
                            type="button"
                            value="Speichern"
                            class="stdButton"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                            onclick="saveNewWorkSession(this)"
                    >
                </div>
            </div>


            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="2">


                <table class="workSessionTable" data-projectId="<?= $project['PROJECT_ID'] ?>">
                    <tr>
                        <th> Beginn der Session</th>
                        <th> Ende der Session</th>
                        <th> Dauer</th>
                        <th> Kommentar</th>
                        <th colspan="2"> Optionen</th>
                    </tr>

                    <?php
                        $workSessions = selectAllWorkSessions($project['PROJECT_ID']);
                        if($workSessions !== false)
                        {
							foreach ($workSessions as $workSession)
                            {
                    ?>
                        <tr>
                            <td>
                                <input
                                        type="time"
                                        name="timeStart"
                                        value="<?= $workSession['TIME_FROM'] ?>"
                                        class="timeSelect"
                                        data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                >
                            </td>
                            <td>
                                <input
                                        type="time"
                                        name="timeEnd"
                                        value="<?= $workSession['TIME_TO'] ?>"
                                        class="timeSelect"
                                        data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                >
                            </td>
                            <td>
                                <input
                                        type="time"
                                        name="timeDiff"
                                        value="<?= 0 /*getDifferenceBetweenTimes($workSession['TIME_FROM'], $workSession['TIME_TO'])*/ ?>"
                                        class="timeSelect"
                                        data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                >
                            </td>
                            <td>
                            <textarea
                                    maxlength="4000"
                                    rows="10"
                                    cols="25"
                                    class="sessionComment"
                                    data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                            ><?= $workSession['COMMENT'] ?></textarea>
                            </td>
                            <td>
                                <input
                                        type="button"
                                        value="Änderungen speichern"
                                        data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                        onclick="updateWorkSession(this)"
                                >
                            </td>
                            <td>
                                <input
                                        type="button"
                                        value="Löschen"
                                        data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                        data-projectId="<?= $project['PROJECT_ID'] ?>"
                                        onclick="deleteWorkSession(this)"
                                >
                            </td>
                        </tr>
                    <?php
							}
                        }
                    ?>

                </table>
            </div>


            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="3">
                CC
            </div>


            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="4">
                <form action="?updateProject=<?= $project['PROJECT_ID']?>" method="post">
                    <h2>
                        Projekteinstellungen
                    </h2>
                    <div>
                        Name des Projektes: <input type="text" name="projectName" required="required" value="<?= $project['PROJECT_NAME']?>">
                    </div>
                    <div>
                        Start des Projektes: <input type="date" name="dateStart" value="<?= $project['DATE_START']?>">
                    </div>
                    <div>
                        Ende des Projektes: <input type="date" name="dateEnd" value="<?= $project['DATE_END']?>">
                    </div>
                    <div>
                        Einkommen: <input type="number" min="0.00" step="0.01" name="income" value="<?= $project['INCOME']?>">
                    </div>
                    <div>
                        Art des Einkommens: <input type="text" name="incomeType" value="<?= $project['INCOME_TYPE']?>">
                    </div>
                    <div>
                        Tägliche Wunscharbeitszeit: <input type="time" name="desiredDaylyWorktime" value="<?= $project['DESIRED_DAYLY_WORKTIME']?>">
                    </div>
                    <div>
                        Stündliches Wunscheinkommen: <input type="number" min="0.00" step="0.01" name="desiredHourlyWage" value="<?= $project['DESIRED_HOURLY_WAGE']?>">
                    </div>
                    <div>
                        <input type="submit" value="Änderungen speichern">
                    </div>
                </form>
                <form action="?deleteProject=<?= $project['PROJECT_ID']?>" method="post">
                    <div>
                        <input type="submit" value="Projekt löschen">
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
?>
