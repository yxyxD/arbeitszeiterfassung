<?php
if($userProjects !== false)
{
    foreach ($userProjects as $row => $project)
    {
        ?>
        <!-- ####################################################################################################### -->
        <!-- Tab Menu -->
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
                     onclick="showTab(this);loadAllCharts(<?= $project['PROJECT_ID'] ?>)">
                    Ergebnisse
                </div>
                <div class="tabMenuItem noselect" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="4"
                     onclick="showTab(this)">
                    Projekteinstellungen
                </div>
            </div>


            <!-- ################################################################################################### -->
            <!-- Create Session Tab -->
            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="1">
                <div class="centering">
                    <div class="clock"></div>
                </div>


                <table class="stdTable">
                    <tr>
                        <td> Beginn der Session: </td>
                        <td>
                            <input
                                    type="time"
                                    name="timeStart"
                                    class="stdInput newSessionTimeStart"
                                    data-projectId="<?= $project['PROJECT_ID'] ?>"
                            >
                        </td>
                    </tr>
                    <tr>
                        <td> Ende der Session: </td>
                        <td>
                            <input
                                    type="time"
                                    name="timeEnd"
                                    class="stdInput newSessionTimeEnd"
                                    data-projectId="<?= $project['PROJECT_ID'] ?>"
                            >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input
                                    type="button"
                                    value="Starte Session"
                                    class="stdButton"
                                    data-projectId="<?= $project['PROJECT_ID'] ?>"
                                    onclick="takeSessionTime(this)"
                            >
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <textarea
                                    maxlength="4000"
                                    rows="10"
                                    cols="100"
                                    class="newSessionComment"
                                    data-projectId="<?= $project['PROJECT_ID'] ?>"
                                    placeholder="--- Anmerkungen (maximal 4000 Zeichen) ---"
                            ></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input
                                    type="button"
                                    value="Speichern"
                                    class="stdButton"
                                    data-projectId="<?= $project['PROJECT_ID'] ?>"
                                    onclick="saveNewWorkSession(this)"
                            >
                        </td>
                    </tr>
                </table>
            </div>


            <!-- ################################################################################################### -->
            <!-- Session Tab -->
            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="2">
                <table class="stdTable workSessionTable" data-projectId="<?= $project['PROJECT_ID'] ?>">
                    <tr>
                        <th> Datum </th>
                        <th> Beginn <br /> der Session</th>
                        <th> Ende <br /> der Session</th>
                        <th> Dauer</th>
                        <th> Kommentar</th>
                        <th colspan="2"> Optionen</th>
                    </tr>

                    <?php
                        $workSessions = selectAllWorkSessions($project['PROJECT_ID']);
                        if($workSessions !== false)
                        {
                            $lastDate = "";
							foreach ($workSessions as $workSession)
                            {
                    ?>
                                <tr>
                                    <td>
										<?= ($workSession['DATE'] !== $lastDate) ? $workSession['DATE'] : "" ?>

										<?php
										$lastDate = $workSession['DATE'];
										?>
                                    </td>
                                    <td>
                                        <input
                                                type="time"
                                                name="timeStart"
                                                value="<?= $workSession['TIME_FROM'] ?>"
                                                class="stdInput"
                                                data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                        >
                                    </td>
                                    <td>
                                        <input
                                                type="time"
                                                name="timeEnd"
                                                value="<?= $workSession['TIME_TO'] ?>"
                                                class="stdInput"
                                                data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                        >
                                    </td>
                                    <td>
                                        <input
                                                type="time"
                                                name="timeDiff"
                                                value="<?= getDifferenceBetweenTimes($workSession['TIME_FROM'], $workSession['TIME_TO'])?>"
                                                class="stdInput"
                                                data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                                readonly
                                        >
                                    </td>
                                    <td>
                                    <textarea
                                            maxlength="4000"
                                            cols="25"
                                            class="sessionComment"
                                            data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                    ><?= $workSession['COMMENT'] ?></textarea>
                                    </td>
                                    <td>
                                        <div onclick="updateWorkSession(this)"
                                             data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                             class="mouseChange"
                                        >
                                            <img
                                                    src="../images/edit-solid.png"
                                                    width="20"
                                                    height="20"
                                            >
                                        </div>
                                    </td>
                                    <td>
                                        <div onclick="deleteWorkSession(this)"
                                             data-sessionId="<?= $workSession['SESSION_ID'] ?>"
                                             class="mouseChange"
                                        >
                                            <img
                                                    src="../images/trash-solid.png"
                                                    width="20"
                                                    height="20"
                                            >
                                        </div>
                                    </td>
                                </tr>
                    <?php
							}
                        }
                    ?>

                </table>
            </div>


            <!-- ################################################################################################### -->
            <!-- Chart Tab -->
            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="3">
                <div>
                    <canvas
                            class="chart"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                            data-chartId="workSessionChart"
                    >
                    </canvas>
                </div>
                <div>
                    <canvas
                            class="chart"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                            data-chartId="workDaysRatioChart"
                    >
                    </canvas>
                </div>
                <div>
                    <canvas
                            class="chart"
                            data-projectId="<?= $project['PROJECT_ID'] ?>"
                            data-chartId="workTimeRatioChart"
                    >
                    </canvas>
                </div>
            </div>


            <!-- ################################################################################################### -->
            <!-- Project Settings Tab -->
            <div class="tabContent" data-projectId="<?= $project['PROJECT_ID'] ?>" data-tabId="4">
                <form action="?updateProject=<?= $project['PROJECT_ID']?>" method="post">


                    <table class="stdTable">
                        <tr>
                            <td colspan="2">
                                <h2>
                                    Projekteinstellungen
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td> Name des Projektes: </td>
                            <td>
                                <input
                                        type="text"
                                        name="projectName"
                                        required="required"
                                        value="<?= $project['PROJECT_NAME']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> Start des Projektes: </td>
                            <td>
                                <input
                                        type="date"
                                        name="dateStart"
                                        value="<?= $project['DATE_START']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> Ende des Projektes: </td>
                            <td>
                                <input
                                        type="date"
                                        name="dateEnd"
                                        value="<?= $project['DATE_END']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> Einkommen: </td>
                            <td>
                                <input
                                        type="number"
                                        min="0.00"
                                        step="0.01"
                                        name="income"
                                        value="<?= $project['INCOME']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> Art des Einkommens: </td>
                            <td>
                                <input
                                        type="text"
                                        name="incomeType"
                                        value="<?= $project['INCOME_TYPE']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> Tägliche Wunscharbeitszeit: </td>
                            <td>
                                <input
                                        type="time"
                                        name="desiredDaylyWorktime"
                                        value="<?= $project['DESIRED_DAYLY_WORKTIME']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td> Stündliches Wunscheinkommen: </td>
                            <td>
                                <input
                                        type="number"
                                        min="0.00"
                                        step="0.01"
                                        name="desiredHourlyWage"
                                        value="<?= $project['DESIRED_HOURLY_WAGE']?>"
                                        class="stdInput"
                                >
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input
                                        type="submit"
                                        value="Änderungen speichern"
                                        class="stdButton"
                                >
                            </td>
                        </tr>
                    </table>
                </form>

                <form action="?deleteProject=<?= $project['PROJECT_ID']?>" method="post">
                    <div>
                        <input type="submit" value="Projekt löschen" class="stdButton">
                    </div>
                </form>
            </div>


        </div>
        <?php
    }
}
?>
