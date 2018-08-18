<div class="projectContent" data-projectId="1">
	<div id="tabMenu">
		<div class="tabMenuItem noselect" data-projectId="1" data-tabId="1" onclick="showTab(this)">
			Neue Session
		</div>
		<div class="tabMenuItem noselect" data-projectId="1" data-tabId="2" onclick="showTab(this)">
			Sessions verwalten
		</div>
		<div class="tabMenuItem noselect" data-projectId="1" data-tabId="3" onclick="showTab(this)">
			Ergebnisse
		</div>
		<div class="tabMenuItem noselect" data-projectId="1" data-tabId="4" onclick="showTab(this)">
			Projekteinstellungen
		</div>
	</div>

	<div class="tabContent" data-projectId="1" data-tabId="1">
		<div class="centering">
			<div class="clock"></div>
		</div>
		<form>
			<div class="sessionCreator">
				<div class="scItem1">
					<div style="display: table-cell;">
						<div class="inlineBlock"> Startzeit: </div>
						<div class="inlineBlock">
							<input
								type="time"
								name="time_start"
								class="timeSelect newSessionTimeStart"
								data-projectId="1"
							>
						</div>
					</div>
				</div>
				<div class="scItem3">
					<div style="display: table-cell;">
						<div class="inlineBlock"> Endzeit: </div>
						<div class="inlineBlock">
							<input type="time" name="time_end" class="timeSelect">
						</div>
					</div>
				</div>
				<div class="scItem5 centering">
					<input
						type="button"
						value="Starte Session"
						class="stdButton"
						data-projectId="1"
						onclick="takeSessionTime(this)"
					>
				</div>
				<div class="scItem6">
					<p> Auswertung </p>
				</div>
				<div class="scItem7"> 7 </div>
				<div class="scItem8"> 8 </div>
				<div class="scItem9"> 9 </div>
				<div class="scItem10">
					<p> Start der Session </p>
				</div>
				<div class="scItem11"> 11 </div>
				<div class="scItem21"> 23 </div>
				<div class="scItem22">
					<p> Ende der Session </p>
				</div>
				<div class="scItem23"> 23 </div>
				<div class="scItem31"> 31 </div>
				<div class="scItem32">
					<p> Gesamtarbeitszeit: </p>
				</div>
				<div class="scItem33"> 33 </div>
				<div class="scItem12"> 12 </div>
				<div class="scItem13">
					<textarea
						maxlength="4000"
						rows="10"
						cols="50"
						placeholder="--- Anmerkungen (maximal 4000 Zeichen) ---"
					>
					</textarea>
				</div>
				<div class="scItem14 centering">
					<input type="reset" value="Zurücksetzen" class="stdButton">
				</div>
				<div class="scItem15 centering">
					<input type="submit" value="Speichern" class="stdButton">
				</div>
			</div>
		</form>
	</div>
	<div class="tabContent" data-projectId="1" data-tabId="2">
		BB
	</div>
	<div class="tabContent" data-projectId="1" data-tabId="3">
		CC
	</div>
	<div class="tabContent" data-projectId="1" data-tabId="4">
		EE
	</div>
</div>
