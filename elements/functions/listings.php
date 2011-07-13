<?PHP
	function courseListings_owner() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && courses.pnmuin = '".$_SESSION[SESSIONPREFIX]['nmuin']."' && courseStatus.curStatus != 'Discard' ORDER BY clisting ASC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<table summary="Conversion Courses" cellpadding="0" cellspacing="0">'.PHP_EOL;
			echo '<thead>'.PHP_EOL;
			echo '  <tr>'.PHP_EOL;
			echo '	<th>Prefix</th>'.PHP_EOL;
			echo '	<th>Listing</th>'.PHP_EOL;
			echo '	<th>Term</th>'.PHP_EOL;
			echo '	<th>Difficulty</th>'.PHP_EOL;
			echo '	<th>% Completed</th>'.PHP_EOL;
			echo '	<th>Assigned To</th>'.PHP_EOL;
			echo '	<th>Status</th>'.PHP_EOL;
			echo '	<th>*</th>'.PHP_EOL;
			echo '  </tr>'.PHP_EOL;
			echo '</thead>'.PHP_EOL;
			echo '<tbody>'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				$compVal = 0;
				$totVal = ($r["syllabus"]+$r["afiles"]+$r["lrngMods"]+$r["assignments"]+$r["aquestions"]+$r["aimages"]+$r["disscussions"]+$r["gradebook"]+$r["camtasia"]+$r["avfiles"]+$r["webbased"]);
				$trclass = (($i%2)==0) ? "light" : "dark";
				echo '  <tr class="'.$trclass.'">'.PHP_EOL;
				echo '	<td>'.$r["cprefix"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["clisting"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["term"].'</td>'.PHP_EOL;
				if($totVal>=0 && $totVal<=3) { $bgcolor = "green"; }
				else if($totVal>=4 && $totVal<=7) { $bgcolor = "orange"; }
				else if($totVal>=8) { $bgcolor = "red"; }
				echo '	<td><span style="background-color:'.$bgcolor.';color:white">&nbsp;'.$totVal.'&nbsp;</span></td>'.PHP_EOL;
				//get the percentage completed
				if($r["syllabusStatus"]=="C") { $compVal += 1; }
				if($r["afilesStatus"]=="C") { $compVal += 1; }
				if($r["lrngModsStatus"]=="C") { $compVal += 1; }
				if($r["assignmentsStatus"]=="C") { $compVal += 1; }
				if($r["aquestionsStatus"]=="C") { $compVal += 1; }
				if($r["aimagesStatus"]=="C") { $compVal += 1; }
				if($r["disscussionsStatus"]=="C") { $compVal += 1; }
				if($r["gradebookStatus"]=="C") { $compVal += 1; }
				if($r["camtasiaStatus"]=="C") { $compVal += 1; }
				if($r["avfilesStatus"]=="C") { $compVal += 1; }
				if($r["webbasedStatus"]=="C") { $compVal += 1; }
				if($compVal==0 && $totVal==0) { $compVal = $totVal = 10; } //make it so if nothing needs to be done, the % complete is 100
				echo '	<td>'.number_format((($compVal/$totVal)*100),1).'%</td>'.PHP_EOL;
				if($r["assignedTo"]=="0") { echo '	<td>Not Assigned</td>'.PHP_EOL; }
				else { echo '	<td>NMU CITE</td>'.PHP_EOL; }
				if($r["curStatus"]=="inProgress") { echo '	<td>In Progress</td>'.PHP_EOL; }
				else if($r["curStatus"]=="waitingConfirm") { echo '	<td>Awaiting Confirmation</td>'.PHP_EOL; }
				else if($r["curStatus"]=="notStarted") { echo '	<td>Not Started</td>'.PHP_EOL; }
				else if($r["curStatus"]=="Complete") { echo '	<td>Migration Complete</td>'.PHP_EOL; }
				echo '	<td><form name="frmList" method="post"><input type="Submit" name="Submit" value="Display"><input type="hidden" name="c" value="'.$r["cid"].'"></form></td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
			}
			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}

	function courseListings_unassigned() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && (courseStatus.assignedTo = 0 || courseStatus.assignedTo = ".$_SESSION[SESSIONPREFIX]['uid'].")  && courseStatus.curStatus != 'Discard' ORDER BY clisting ASC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<table summary="Conversion Courses" cellpadding="0" cellspacing="0">'.PHP_EOL;
			echo '<thead>'.PHP_EOL;
			echo '  <tr>'.PHP_EOL;
			echo '	<th>Prefix</th>'.PHP_EOL;
			echo '	<th>Listing</th>'.PHP_EOL;
			echo '	<th>Instructor</th>'.PHP_EOL;
			echo '	<th>Term</th>'.PHP_EOL;
			echo '	<th>Difficulty</th>'.PHP_EOL;
			echo '	<th>% Completed</th>'.PHP_EOL;
			echo '	<th>Assigned To</th>'.PHP_EOL;
			echo '	<th>Status</th>'.PHP_EOL;
			echo '	<th>*</th>'.PHP_EOL;
			echo '  </tr>'.PHP_EOL;
			echo '</thead>'.PHP_EOL;
			echo '<tbody>'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				$compVal = 0;
				$totVal = ($r["syllabus"]+$r["afiles"]+$r["lrngMods"]+$r["assignments"]+$r["aquestions"]+$r["aimages"]+$r["disscussions"]+$r["gradebook"]+$r["camtasia"]+$r["avfiles"]+$r["webbased"]);
				$trclass = (($i%2)==0) ? "light" : "dark";
				echo '  <tr class="'.$trclass.'">'.PHP_EOL;
				echo '	<td>'.$r["cprefix"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["clisting"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["userid"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["term"].'</td>'.PHP_EOL;
				if($totVal>=0 && $totVal<=3) { $bgcolor = "green"; }
				else if($totVal>=4 && $totVal<=7) { $bgcolor = "orange"; }
				else if($totVal>=8) { $bgcolor = "red"; }
				echo '	<td><span style="background-color:'.$bgcolor.';color:white">&nbsp;'.$totVal.'&nbsp;</span></td>'.PHP_EOL;
				//get the percentage completed
				if($r["syllabusStatus"]=="C") { $compVal += 1; }
				if($r["afilesStatus"]=="C") { $compVal += 1; }
				if($r["lrngModsStatus"]=="C") { $compVal += 1; }
				if($r["assignmentsStatus"]=="C") { $compVal += 1; }
				if($r["aquestionsStatus"]=="C") { $compVal += 1; }
				if($r["aimagesStatus"]=="C") { $compVal += 1; }
				if($r["disscussionsStatus"]=="C") { $compVal += 1; }
				if($r["gradebookStatus"]=="C") { $compVal += 1; }
				if($r["camtasiaStatus"]=="C") { $compVal += 1; }
				if($r["avfilesStatus"]=="C") { $compVal += 1; }
				if($r["webbasedStatus"]=="C") { $compVal += 1; }
				if($compVal==0 && $totVal==0) { $compVal = $totVal = 10; } //make it so if nothing needs to be done, the % complete is 100
				echo '	<td>'.number_format((($compVal/$totVal)*100),1).'%</td>'.PHP_EOL;
				if($r["assignedTo"]=="0") { echo '	<td>Not Assigned</td>'.PHP_EOL; }
				else {
					$user_SQL = "SELECT * FROM user WHERE uid = ".$r["assignedTo"];
					$userRes = $vusr->db_query($user_SQL);
					while($u=$vusr->db_fetchrow($userRes)) { echo '	<td>'.$u["lname"].', '.$u["fname"].'</td>'.PHP_EOL; }
				}
				if($r["curStatus"]=="inProgress") { echo '	<td>In Progress</td>'.PHP_EOL; }
				else if($r["curStatus"]=="waitingConfirm") { echo '	<td>Awaiting Confirmation</td>'.PHP_EOL; }
				else if($r["curStatus"]=="notStarted") { echo '	<td>Not Started</td>'.PHP_EOL; }
				else if($r["curStatus"]=="Complete") { echo '	<td>Migration Complete</td>'.PHP_EOL; }
				echo '	<td><form name="frmList" method="post"><input type="Submit" name="Submit" value="Display"><input type="hidden" name="c" value="'.$r["cid"].'"></form></td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
			}
			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
	
	function courseListings_myCourses() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && courseStatus.assignedTo = ".$_SESSION[SESSIONPREFIX]['uid']." && courseStatus.curStatus != 'Discard' ORDER BY clisting ASC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<table summary="Conversion Courses" cellpadding="0" cellspacing="0">'.PHP_EOL;
			echo '<thead>'.PHP_EOL;
			echo '  <tr>'.PHP_EOL;
			echo '	<th>Prefix</th>'.PHP_EOL;
			echo '	<th>Listing</th>'.PHP_EOL;
			echo '	<th>Instructor</th>'.PHP_EOL;
			echo '	<th>Term</th>'.PHP_EOL;
			echo '	<th>Difficulty</th>'.PHP_EOL;
			echo '	<th>% Completed</th>'.PHP_EOL;
			echo '	<th>Assigned To</th>'.PHP_EOL;
			echo '	<th>Status</th>'.PHP_EOL;
			echo '	<th>*</th>'.PHP_EOL;
			echo '  </tr>'.PHP_EOL;
			echo '</thead>'.PHP_EOL;
			echo '<tbody>'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				$compVal = 0;
				$totVal = ($r["syllabus"]+$r["afiles"]+$r["lrngMods"]+$r["assignments"]+$r["aquestions"]+$r["aimages"]+$r["disscussions"]+$r["gradebook"]+$r["camtasia"]+$r["avfiles"]+$r["webbased"]);
				$trclass = (($i%2)==0) ? "light" : "dark";
				echo '  <tr class="'.$trclass.'">'.PHP_EOL;
				echo '	<td>'.$r["cprefix"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["clisting"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["userid"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["term"].'</td>'.PHP_EOL;
				if($totVal>=0 && $totVal<=3) { $bgcolor = "green"; }
				else if($totVal>=4 && $totVal<=7) { $bgcolor = "orange"; }
				else if($totVal>=8) { $bgcolor = "red"; }
				echo '	<td><span style="background-color:'.$bgcolor.';color:white">&nbsp;'.$totVal.'&nbsp;</span></td>'.PHP_EOL;
				//get the percentage completed
				if($r["syllabusStatus"]=="C") { $compVal += 1; }
				if($r["afilesStatus"]=="C") { $compVal += 1; }
				if($r["lrngModsStatus"]=="C") { $compVal += 1; }
				if($r["assignmentsStatus"]=="C") { $compVal += 1; }
				if($r["aquestionsStatus"]=="C") { $compVal += 1; }
				if($r["aimagesStatus"]=="C") { $compVal += 1; }
				if($r["disscussionsStatus"]=="C") { $compVal += 1; }
				if($r["gradebookStatus"]=="C") { $compVal += 1; }
				if($r["camtasiaStatus"]=="C") { $compVal += 1; }
				if($r["avfilesStatus"]=="C") { $compVal += 1; }
				if($r["webbasedStatus"]=="C") { $compVal += 1; }
				if($compVal==0 && $totVal==0) { $compVal = $totVal = 10; } //make it so if nothing needs to be done, the % complete is 100
				echo '	<td>'.number_format((($compVal/$totVal)*100),1).'%</td>'.PHP_EOL;
				if($r["assignedTo"]=="0") { echo '	<td>Not Assigned</td>'.PHP_EOL; }
				else {
					$user_SQL = "SELECT * FROM user WHERE uid = ".$r["assignedTo"];
					$userRes = $vusr->db_query($user_SQL);
					while($u=$vusr->db_fetchrow($userRes)) { echo '	<td>'.$u["lname"].', '.$u["fname"].'</td>'.PHP_EOL; }
				}
				if($r["curStatus"]=="inProgress") { echo '	<td>In Progress</td>'.PHP_EOL; }
				else if($r["curStatus"]=="waitingConfirm") { echo '	<td>Awaiting Confirmation</td>'.PHP_EOL; }
				else if($r["curStatus"]=="notStarted") { echo '	<td>Not Started</td>'.PHP_EOL; }
				else if($r["curStatus"]=="Complete") { echo '	<td>Migration Complete</td>'.PHP_EOL; }
				echo '	<td><form name="frmList" method="post"><input type="Submit" name="Submit" value="Display"><input type="hidden" name="c" value="'.$r["cid"].'"></form></td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
			}
			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
		}
		echo '<p>You are currently viewing your assigned courses. <a href=?s='.md5("viewUnassigned".session_id()).'>Click Here</a> to view all un-assigned courses as well.</p>'.PHP_EOL;
		$vusr->db_close();
		unset($vusr);
	}
	
	function courseListings_CurrentAll() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && courseStatus.curStatus != 'Discard' ORDER BY clisting ASC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<table summary="Conversion Courses" cellpadding="0" cellspacing="0">'.PHP_EOL;
			echo '<thead>'.PHP_EOL;
			echo '  <tr>'.PHP_EOL;
			echo '	<th>Prefix</th>'.PHP_EOL;
			echo '	<th>Listing</th>'.PHP_EOL;
			echo '	<th>Instructor</th>'.PHP_EOL;
			echo '	<th>Term</th>'.PHP_EOL;
			echo '	<th>Difficulty</th>'.PHP_EOL;
			echo '	<th>% Completed</th>'.PHP_EOL;
			echo '	<th>Assigned To</th>'.PHP_EOL;
			echo '	<th>Status</th>'.PHP_EOL;
			echo '	<th>*</th>'.PHP_EOL;
			echo '  </tr>'.PHP_EOL;
			echo '</thead>'.PHP_EOL;
			echo '<tbody>'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				$compVal = 0;
				$totVal = ($r["syllabus"]+$r["afiles"]+$r["lrngMods"]+$r["assignments"]+$r["aquestions"]+$r["aimages"]+$r["disscussions"]+$r["gradebook"]+$r["camtasia"]+$r["avfiles"]+$r["webbased"]);
				$trclass = (($i%2)==0) ? "light" : "dark";
				echo '  <tr class="'.$trclass.'">'.PHP_EOL;
				echo '	<td>'.$r["cprefix"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["clisting"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["userid"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["term"].'</td>'.PHP_EOL;
				if($totVal>=0 && $totVal<=3) { $bgcolor = "green"; }
				else if($totVal>=4 && $totVal<=7) { $bgcolor = "orange"; }
				else if($totVal>=8) { $bgcolor = "red"; }
				echo '	<td><span style="background-color:'.$bgcolor.';color:white">&nbsp;'.$totVal.'&nbsp;</span></td>'.PHP_EOL;
				//get the percentage completed
				if($r["syllabusStatus"]=="C") { $compVal += 1; }
				if($r["afilesStatus"]=="C") { $compVal += 1; }
				if($r["lrngModsStatus"]=="C") { $compVal += 1; }
				if($r["assignmentsStatus"]=="C") { $compVal += 1; }
				if($r["aquestionsStatus"]=="C") { $compVal += 1; }
				if($r["aimagesStatus"]=="C") { $compVal += 1; }
				if($r["disscussionsStatus"]=="C") { $compVal += 1; }
				if($r["gradebookStatus"]=="C") { $compVal += 1; }
				if($r["camtasiaStatus"]=="C") { $compVal += 1; }
				if($r["avfilesStatus"]=="C") { $compVal += 1; }
				if($r["webbasedStatus"]=="C") { $compVal += 1; }
				if($compVal==0 && $totVal==0) { $compVal = $totVal = 10; } //make it so if nothing needs to be done, the % complete is 100
				echo '	<td>'.number_format((($compVal/$totVal)*100),1).'%</td>'.PHP_EOL;
				if($r["assignedTo"]=="0") { echo '	<td>Not Assigned</td>'.PHP_EOL; }
				else {
					$user_SQL = "SELECT * FROM user WHERE uid = ".$r["assignedTo"];
					$userRes = $vusr->db_query($user_SQL);
					while($u=$vusr->db_fetchrow($userRes)) { echo '	<td>'.$u["lname"].', '.$u["fname"].'</td>'.PHP_EOL; }
				}
				if($r["curStatus"]=="inProgress") { echo '	<td>In Progress</td>'.PHP_EOL; }
				else if($r["curStatus"]=="waitingConfirm") { echo '	<td>Awaiting Confirmation</td>'.PHP_EOL; }
				else if($r["curStatus"]=="notStarted") { echo '	<td>Not Started</td>'.PHP_EOL; }
				else if($r["curStatus"]=="Complete") { echo '	<td>Migration Complete</td>'.PHP_EOL; }
				echo '	<td><form name="frmList" method="post"><input type="Submit" name="Submit" value="Display"><input type="hidden" name="c" value="'.$r["cid"].'"></form></td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
			}
			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
	
?>