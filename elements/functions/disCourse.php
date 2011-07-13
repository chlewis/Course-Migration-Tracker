<?PHP
	function addCourse_addIt($prefix="",$listing="",$pnmuin="",$term="",$csize="",$syllabus="",$afiles="",$lrngMods="",$assignments="",$aques="",$aimgs="",$discuss="",$gradebook="",$camtasia="",$avfiles="",$webbased="",$notes="") {
		$_SESSION[SESSIONPREFIX]['addCourse_error'] = "";
		$_SESSION[SESSIONPREFIX]['addCourse_prefix'] = $prefix;
		$_SESSION[SESSIONPREFIX]['addCourse_listing'] = $listing;
		$_SESSION[SESSIONPREFIX]['addCourse_pnumin'] = $pnmuin;
		$_SESSION[SESSIONPREFIX]['addCourse_term'] = $term;
		$_SESSION[SESSIONPREFIX]['addCourse_size'] = $csize;
		$_SESSION[SESSIONPREFIX]['addCourse_notes'] = $notes;
		if(strlen(trim($prefix))<=0) { $_SESSION[SESSIONPREFIX]['addCourse_error'] .= "You must supply a course prefix.<br />"; }
		if(strlen(trim($listing))<=0) { $_SESSION[SESSIONPREFIX]['addCourse_error'] .= "You must supply a course listing.<br />"; }
		if(strlen(trim($pnmuin))<=0) { $_SESSION[SESSIONPREFIX]['addCourse_error'] .= "You must supply a course instructor.<br />"; }
		if(strlen(trim($term))<=0) { $_SESSION[SESSIONPREFIX]['addCourse_error'] .= "You must supply a term.<br />"; }
		if(strlen(trim($_SESSION[SESSIONPREFIX]['addCourse_error']))<=0) {
			$oraDB = new db;
			$oraConn = $oraDB->db_connect("", $_SESSION[SESSIONPREFIX]['orauser'], $_SESSION[SESSIONPREFIX]['orapass'], "", "oracle");
			$sql = "SELECT NMUID FROM HELPDESK.TLC_CALLERS WHERE LOWER(EMAIL_ID) = '".$pnmuin."'";
			$oraRes = $oraDB->db_query($sql);
			if($oraDB->db_numrows($oraRes)>0) {
				while($ora=$oraDB->db_fetchrow($oraRes)) { $nmuin = $ora["nmuid"]; }
			} else { $nmuin = ""; }
			$oraDB->db_close();
			unset($oraDB);
			$vusr = new db;
			$dbConn = $vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
			$course_SQL = "INSERT INTO courses(cprefix,clisting,pnmuin,userid,term,csize,syllabus,afiles,lrngMods,assignments,aquestions,aimages,disscussions,gradebook,camtasia,avfiles,webbased,notes,dateAdded) VALUES(";
			$course_SQL .= "'".mysql_real_escape_string($prefix)."',";
			$course_SQL .= "'".mysql_real_escape_string($listing)."',";
			$course_SQL .= (strlen($nmuin)>0) ? "'".mysql_real_escape_string($nmuin)."'," : "NULL,";
			$course_SQL .= "'".mysql_real_escape_string($pnmuin)."',";
			$course_SQL .= "'".mysql_real_escape_string($term)."',";
			$course_SQL .= "".(int)mysql_real_escape_string($csize).",";
			$course_SQL .= "".(int)mysql_real_escape_string($syllabus).",";
			$course_SQL .= "".(int)mysql_real_escape_string($afiles).",";
			$course_SQL .= "".(int)mysql_real_escape_string($lrngMods).",";
			$course_SQL .= "".(int)mysql_real_escape_string($assignments).",";
			$course_SQL .= "".(int)mysql_real_escape_string($aques).",";
			$course_SQL .= "".(int)mysql_real_escape_string($aimgs).",";
			$course_SQL .= "".(int)mysql_real_escape_string($discuss).",";
			$course_SQL .= "".(int)mysql_real_escape_string($gradebook).",";
			$course_SQL .= "".(int)mysql_real_escape_string($camtasia).",";
			$course_SQL .= "".(int)mysql_real_escape_string($avfiles).",";
			$course_SQL .= "".(int)mysql_real_escape_string($webbased).",";
			$course_SQL .= "'".mysql_real_escape_string($notes)."',NOW())";
			$adduserRes = $vusr->db_query($course_SQL);
			$cid = mysql_insert_id($dbConn);
			$courseStatus_SQL = "INSERT INTO courseStatus(cid,curStatus,assignedTo,syllabusStatus,afilesStatus,lrngModsStatus,assignmentsStatus,aquestionsStatus,aimagesStatus,disscussionsStatus,gradebookStatus,camtasiaStatus,avfilesStatus,webbasedStatus) VALUES(";
			$courseStatus_SQL .= "".$cid.",'notStarted',0,";
			$courseStatus_SQL .= ((int)$syllabus==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$afiles==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$lrngMods==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$assignments==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$aques==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$aimgs==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$discuss==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$gradebook==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$camtasia==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$avfiles==1) ? "'P'," : "'NA',";
			$courseStatus_SQL .= ((int)$webbased==1) ? "'P')" : "'NA')";
			$adduserRes = $vusr->db_query($courseStatus_SQL);
			$vusr->db_close();
			unset($vusr);
			unset($_SESSION[SESSIONPREFIX]['addCourse_prefix']);
			unset($_SESSION[SESSIONPREFIX]['addCourse_listing']);
			unset($_SESSION[SESSIONPREFIX]['addCourse_pnumin']);
			unset($_SESSION[SESSIONPREFIX]['addCourse_term']);
			unset($_SESSION[SESSIONPREFIX]['addCourse_size']);
			unset($_SESSION[SESSIONPREFIX]['addCourse_notes']);
			unset($_SESSION[SESSIONPREFIX]['addCourse_error']);
			displayCourse($cid);
		} else { addCourse(); }
	}

	function addCourse() {
		echo '<div class="wrapper col4">'.PHP_EOL;
		echo '<div id="container">'.PHP_EOL;
		echo '<div id="content">'.PHP_EOL;
		echo '<h1>Add a Course</h1>'.PHP_EOL;
		if(isset($_SESSION[SESSIONPREFIX]['addCourse_error']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_error'])>0) {
			echo '<p><strong><font color="red">Error:</font> '.$_SESSION[SESSIONPREFIX]['addCourse_error'].'</strong></p>'.PHP_EOL;
			unset($_SESSION[SESSIONPREFIX]['addCourse_error']);
		}
		echo '<p><form name="frmCourse" method="post" />'.PHP_EOL;
		echo '<table width="455" border="0" cellspacing="3" cellpadding="0">'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td width="146" align="right">Prefix:</td>'.PHP_EOL;
		echo '	<td width="15">&nbsp;</td>'.PHP_EOL;
		$value = (isset($_SESSION[SESSIONPREFIX]['addCourse_prefix']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_prefix'])>0) ? $_SESSION[SESSIONPREFIX]['addCourse_prefix'] : "";
		echo '	<td width="282"><input type="text" name="txtPrefix" value="'.$value.'"/></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Listing:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		$value = (isset($_SESSION[SESSIONPREFIX]['addCourse_listing']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_listing'])>0) ? $_SESSION[SESSIONPREFIX]['addCourse_listing'] : "";
		echo '	<td><input type="text" name="txtListing" value="'.$value.'"/></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Instructor (username):</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		$value = (isset($_SESSION[SESSIONPREFIX]['addCourse_pnumin']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_pnumin'])>0) ? $_SESSION[SESSIONPREFIX]['addCourse_pnumin'] : "";
		echo '	<td><input type="text" name="txtInmuin" value="'.$value.'"/></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Term:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		$value = (isset($_SESSION[SESSIONPREFIX]['addCourse_term']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_term'])>0) ? $_SESSION[SESSIONPREFIX]['addCourse_term'] : "";
		echo '	<td><input type="text" name="txtTerm" value="'.$value.'"/></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Course Size:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		$value = (isset($_SESSION[SESSIONPREFIX]['addCourse_size']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_size'])>0) ? $_SESSION[SESSIONPREFIX]['addCourse_size'] : "";
		echo '	<td><input type="text" name="txtSize" value="'.$value.'"/></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Syllabus:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selSyllabus">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Additional Files:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selAfiles">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Folders/Lrng. Mods:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selLrngmods">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Assignments:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selAssignments">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Assess/Questions:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selAques">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Assess w/Images:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selAimgs">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Discussion:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selDiscussion">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Gradebook:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selGradebook">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Camtasia/Studymate:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selCamtasia">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Audio/Video Files:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selAVfiles">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Web-based:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><select name="selWebbased">'.PHP_EOL;
		echo '		<option value="1">Yes</option>'.PHP_EOL;
		echo '		<option value="0">No</option>'.PHP_EOL;
		echo '	</select></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">Notes:</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		$value = (isset($_SESSION[SESSIONPREFIX]['addCourse_notes']) && strlen($_SESSION[SESSIONPREFIX]['addCourse_notes'])>0) ? $_SESSION[SESSIONPREFIX]['addCourse_notes'] : "";
		echo '	<td rowspan="2"><textarea name="txtNotes">'.$value.'</textarea></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '  <tr>'.PHP_EOL;
		echo '	<td align="right">&nbsp;</td>'.PHP_EOL;
		echo '	<td>&nbsp;</td>'.PHP_EOL;
		echo '	<td><input type="submit" id="submit" name="Submit" value="Add Course"/>&nbsp;&nbsp;<input type="submit" id="submit" name="Submit" value="Cancel"/></td>'.PHP_EOL;
		echo '  </tr>'.PHP_EOL;
		echo '</table>'.PHP_EOL;
		echo '</form></p>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '<div class="clear"></div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		unset($_SESSION[SESSIONPREFIX]['addCourse_prefix']);
		unset($_SESSION[SESSIONPREFIX]['addCourse_listing']);
		unset($_SESSION[SESSIONPREFIX]['addCourse_pnumin']);
		unset($_SESSION[SESSIONPREFIX]['addCourse_term']);
		unset($_SESSION[SESSIONPREFIX]['addCourse_size']);
		unset($_SESSION[SESSIONPREFIX]['addCourse_notes']);
		unset($_SESSION[SESSIONPREFIX]['addCourse_error']);
	}

	function confirmCourse($cid=0,$approval="",$anotes="") {
		$_SESSION[SESSIONPREFIX]['approvalErr'] = "";
		if(strlen(trim($approval))>0) {
			if($approval=="0" && strlen(trim($anotes))<=0) { $_SESSION[SESSIONPREFIX]['approvalErr'] = "Please provide a description of the errors in the text field."; }
		} else { $_SESSION[SESSIONPREFIX]['approvalErr'] = "You must select one of the responses."; }
		if(strlen($_SESSION[SESSIONPREFIX]['approvalErr'])<=0) {
			$vusr = new db;
			$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
			//if the response is good close it out
			if($approval=="1") {
				$query = "UPDATE courseStatus SET curStatus = 'Complete' WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
				$result = $vusr->db_query($query);
				if(strlen(trim($anotes))>0) {
					$contact_SQL = "INSERT INTO courseContact(cid,uid,contactDate,toEadd,contactText) VALUES(".mysql_real_escape_string($cid).",".$_SESSION[SESSIONPREFIX]['uid'].",NOW(),'cite','".mysql_real_escape_string($anotes)."')";
					$contactRes = $vusr->db_query($contact_SQL);
				}
			} else {
				//if the response is bad, add the note and change the status back to in progress
				$query = "UPDATE courseStatus SET curStatus = 'inProgress' WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
				$result = $vusr->db_query($query);
				$contact_SQL = "INSERT INTO courseContact(cid,uid,contactDate,toEadd,contactText) VALUES(".mysql_real_escape_string($cid).",".$_SESSION[SESSIONPREFIX]['uid'].",NOW(),'cite','".mysql_real_escape_string($anotes)."')";
				$contactRes = $vusr->db_query($contact_SQL);
			}
			$vusr->db_close();
			unset($vusr);
		}
		displayCourse($cid);
	}

	function saveCourse($cid=0,$prefix="",$listing="",$pnmuin="",$term="",$syllabus="",$afiles="",$lrngMods="",$assignments="",$aques="",$aimgs="",$discuss="",$gradebook="",$camtasia="",$avfiles="",$webbased="",$notes="",$muid="",$mstatus="") {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && courses.cid = ".mysql_real_escape_string($cid)." LIMIT 1";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			$cupdtFlg = false;
			$csupdtFlg = false;
			$cupdt_SQL = "UPDATE courses SET ";
			$csupdt_SQL = "UPDATE courseStatus SET ";
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				//compare and update all values
				if(strtoupper(trim($r["cprefix"]))!=strtoupper(trim($prefix))) {
					$cupdt_SQL .= "cprefix = '".$prefix."',";
					$cupdtFlg = true;
				}
				if(strtoupper(trim($r["clisting"]))!=strtoupper(trim($listing))) {
					$cupdt_SQL .= "clisting = '".$listing."',";
					$cupdtFlg = true;
				}
				if(strtoupper(trim($r["pnmuin"]))!=strtoupper(trim($pnmuin))) {
					$cupdt_SQL .= "pnmuin = '".$pnmuin."',";
					$cupdtFlg = true;
				}
				if(strtoupper(trim($r["term"]))!=strtoupper(trim($term))) {
					$cupdt_SQL .= "term = '".$term."',";
					$cupdtFlg = true;
				}
				if(strtoupper(trim($r["notes"]))!=strtoupper(trim($notes))) {
					$cupdt_SQL .= "notes = '".mysql_real_escape_string($notes)."',";
					$cupdtFlg = true;
				}
				if(strtoupper(trim($r["syllabusStatus"]))!=strtoupper(trim($syllabus))) {
					$csupdt_SQL .= "syllabusStatus = '".$syllabus."',syllabusCompleteDate=NOW(),syllabusCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($syllabus=="P" || $syllabus=="C") { $cupdt_SQL .= "syllabus = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "syllabus = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["afilesStatus"]))!=strtoupper(trim($afiles))) {
					$csupdt_SQL .= "afilesStatus = '".$afiles."',afilesCompleteDate=NOW(),afilesCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($afiles=="P" || $afiles=="C") { $cupdt_SQL .= "afiles = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "afiles = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["lrngModsStatus"]))!=strtoupper(trim($lrngMods))) {
					$csupdt_SQL .= "lrngModsStatus = '".$lrngMods."',lrngModsCompleteDate=NOW(),lrngModsCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($lrngMods=="P" || $lrngMods=="C") { $cupdt_SQL .= "lrngMods = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "lrngMods = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["assignmentsStatus"]))!=strtoupper(trim($assignments))) {
					$csupdt_SQL .= "assignmentsStatus = '".$assignments."',assignmentsCompleteDate=NOW(),assignmentsCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($assignments=="P" || $assignments=="C") { $cupdt_SQL .= "assignments = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "assignments = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["aquestionsStatus"]))!=strtoupper(trim($aques))) {
					$csupdt_SQL .= "aquestionsStatus = '".$aques."',aquestionsCompleteDate=NOW(),aquestionsCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($aques=="P" || $aques=="C") { $cupdt_SQL .= "aquestions = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "aquestions = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["aimagesStatus"]))!=strtoupper(trim($aimgs))) {
					$csupdt_SQL .= "aimagesStatus = '".$aimgs."',aimagesCompleteDate=NOW(),aimagesCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($aimgs=="P" || $aimgs=="C") { $cupdt_SQL .= "aimages = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "aimages = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["disscussionsStatus"]))!=strtoupper(trim($discuss))) {
					$csupdt_SQL .= "disscussionsStatus = '".$discuss."',disscussionsCompleteDate=NOW(),disscussionsCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($discuss=="P" || $discuss=="C") { $cupdt_SQL .= "disscussions = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "disscussions = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["gradebookStatus"]))!=strtoupper(trim($gradebook))) {
					$csupdt_SQL .= "gradebookStatus = '".$gradebook."',gradebookCompleteDate=NOW(),gradebookCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($gradebook=="P" || $gradebook=="C") { $cupdt_SQL .= "gradebook = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "gradebook = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["camtasiaStatus"]))!=strtoupper(trim($camtasia))) {
					$csupdt_SQL .= "camtasiaStatus = '".$camtasia."',camtasiaCompleteDate=NOW(),camtasiaCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($camtasia=="P" || $camtasia=="C") { $cupdt_SQL .= "camtasia = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "camtasia = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["avfilesStatus"]))!=strtoupper(trim($avfiles))) {
					$csupdt_SQL .= "avfilesStatus = '".$avfiles."',avfilesCompleteDate=NOW(),avfilesCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($avfiles=="P" || $avfiles=="C") { $cupdt_SQL .= "avfiles = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "avfiles = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["webbasedStatus"]))!=strtoupper(trim($webbased))) {
					$csupdt_SQL .= "webbasedStatus = '".$webbased."',webbasedCompleteDate=NOW(),webbasedCompleteUid=".$_SESSION[SESSIONPREFIX]['uid'].",";
					if($webbased=="P" || $webbased=="C") { $cupdt_SQL .= "webbased = 1,"; $cupdtFlg = true; }
					else { $cupdt_SQL .= "webbased = 0,"; $cupdtFlg = true; }
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["assignedTo"]))!=strtoupper(trim($muid))) {
					$csupdt_SQL .= "assignedTo = ".$muid.",";
					$csupdtFlg = true;
				}
				if(strtoupper(trim($r["curStatus"]))!=strtoupper(trim($mstatus))) {
					$csupdt_SQL .= "curStatus = '".$mstatus."',";
					if($mstatus=="waitingConfirm") { $csupdt_SQL .= "confirmDate = NOW(),"; }
					$csupdtFlg = true;
				}
			}
			if($cupdtFlg) {
				$cupdt_SQL = substr($cupdt_SQL,0,-1);
				$cupdt_SQL .= " WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
				//echo $cupdt_SQL."<br />";
				$result = $vusr->db_query($cupdt_SQL);
			}
			if($csupdtFlg) {
				$csupdt_SQL = substr($csupdt_SQL,0,-1);
				$csupdt_SQL .= " WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
				//echo $csupdt_SQL."<br />";
				$result = $vusr->db_query($csupdt_SQL);
			}
		}
		$vusr->db_close();
		unset($vusr);
		displayCourse($cid);
	}

	function contactInstructor($cid=0,$peadd="",$textBody="",$toSend="") {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && courses.cid = ".mysql_real_escape_string($cid)." LIMIT 1";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			while($r=$vusr->db_fetchrow($result)) { $course = $r["clisting"]; }
			if($toSend=="on" && strlen(base64_decode(trim($peadd)))>0) {
				$mail = new PHPMailer();
				if($_SESSION[SESSIONPREFIX]['gid']=="3") {
					$mail->From = $_SESSION[SESSIONPREFIX]['username'].'@nmu.edu';
					$mail->FromName = $_SESSION[SESSIONPREFIX]['fullname'];
				} else {
					$mail->From = EMAILADDR;
					$mail->FromName = EMAILFROM;
				}
				$mail->Subject = "Course Conversion to EduCat: ".$course;
				$mail->AltBody = "You will need to use an HTML viewer to view this email.";
				$body = '<HTML><HEAD><META HTTP-EQUIV="content-type" CONTENT="text/html;charset=ISO-8859-1"></HEAD><BODY>';
				$body .= nl2br($textBody);
				$body .= '<p>&nbsp;</p>'.PHP_EOL;
				$body .= '<p>Please note: you can access EduCat through the following url: <a href="'.MOODLEURL.'">'.MOODLEURL.'</a></p>'.PHP_EOL;
				$body .= '</BODY></HTML>';
				$body = eregi_replace("[\]",'',$body);
				$mail->MsgHTML($body);
				$mail->AddAddress(base64_decode($peadd)."@nmu.edu");
				//$mail->AddAddress("clewis@nmu.edu");
				if($mail->Send()) {
					$contact_SQL = "INSERT INTO courseContact(cid,uid,contactDate,toEadd,contactText) VALUES(".mysql_real_escape_string($cid).",".$_SESSION[SESSIONPREFIX]['uid'].",NOW(),'".base64_decode($peadd)."','".mysql_real_escape_string($textBody)."')";
					$contactRes = $vusr->db_query($contact_SQL);
					if($_SESSION[SESSIONPREFIX]['gid']=="1" || $_SESSION[SESSIONPREFIX]['gid']=="2") {
						//$update_SQL = "UPDATE courseStatus SET curStatus = 'waitingConfirm' WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
						//$updtRes = $vusr->db_query($update_SQL);
					}
				} else { echo "Error sending Message!"; }
				unset($mail);
			} else {
				$contact_SQL = "INSERT INTO courseContact(cid,uid,contactDate,toEadd,contactText) VALUES(".mysql_real_escape_string($cid).",".$_SESSION[SESSIONPREFIX]['uid'].",NOW(),'".base64_decode($peadd)."','".mysql_real_escape_string($textBody)."')";
				$contactRes = $vusr->db_query($contact_SQL);
			}
		}
		$vusr->db_close();
		unset($vusr);
		displayCourse($cid);
	}
	
	function assignCourseToMe($cid=0) {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courseStatus WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			$assign_SQL = "UPDATE courseStatus SET assignedTo = ".$_SESSION[SESSIONPREFIX]['uid'].", curStatus = 'inProgress' WHERE cid = ".mysql_real_escape_string($cid)." LIMIT 1";
			$assRes = $vusr->db_query($assign_SQL);
		}
		$vusr->db_close();
		unset($vusr);
		displayCourse($cid);
	}

	function displayCourse($cid=0) {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT * FROM courses,courseStatus WHERE courses.cid = courseStatus.cid && courses.cid = ".mysql_real_escape_string($cid)." LIMIT 1";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			$confStatus = 0;
			echo '<div class="wrapper col4">'.PHP_EOL;
			echo '<div id="container">'.PHP_EOL;
			echo '<div id="content">'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				echo '<h1>'.$r["cprefix"].' - '.$r["term"].'</h1>'.PHP_EOL;
				echo '<p><form name="frmCourse" method="post" />'.PHP_EOL;
				echo '<table width="455" border="0" cellspacing="3" cellpadding="0">'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td width="146" align="right">Prefix:</td>'.PHP_EOL;
				echo '	<td width="15">&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1") { echo '	<td width="282"><input type="text" name="txtPrefix" value="'.$r["cprefix"].'"/></td>'.PHP_EOL; }
				else { echo '	<td width="282"><input type="hidden" name="txtPrefix" value="'.$r["cprefix"].'"/>'.$r["cprefix"].'</td>'.PHP_EOL; }
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Listing:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1") { echo '	<td><input type="text" name="txtListing" value="'.$r["clisting"].'"/></td>'.PHP_EOL; }
				else { echo '	<td><input type="hidden" name="txtListing" value="'.$r["clisting"].'"/>'.$r["clisting"].'</td>'.PHP_EOL; }
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Instructor:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if(strlen($r["pnmuin"])>0) {
					$oraDB = new db;
					$oraConn = $oraDB->db_connect("", $_SESSION[SESSIONPREFIX]['orauser'], $_SESSION[SESSIONPREFIX]['orapass'], "", "oracle");
					$sql = "SELECT EMAIL_ID,LAST_NAME,FIRST_NAME FROM HELPDESK.TLC_CALLERS WHERE NMUID = '".$r["pnmuin"]."'";
					$oraRes = $oraDB->db_query($sql);
					for($j=0;$ora=$oraDB->db_fetchrow($oraRes);$i++) {
						$pName = ucfirst(strtolower($ora["first_name"]))." ".ucfirst(strtolower($ora["last_name"]));
						$pEadd = strtolower($ora["email_id"]);
					}
					$oraDB->db_close();
					unset($oraDB);
				}
				if($_SESSION[SESSIONPREFIX]['gid']=="1") { 
					if(strlen($r["pnmuin"])>0) { echo '	<td><input type="text" name="txtInmuin" value="'.$r["pnmuin"].'"/></td>'.PHP_EOL; }
					else { echo '	<td><input type="text" name="txtInmuin" value="No Instructor"/></td>'.PHP_EOL; }
				} else { 
					if(strlen($r["pnmuin"])>0) { echo '	<td><input type="hidden" name="txtInmuin" value="'.$r["pnmuin"].'"/>'.$pName.' ('.$pEadd.')</td>'.PHP_EOL; }
					else { echo '	<td><input type="hidden" name="txtInmuin" value="'.$r["pnmuin"].'"/>No Instructor</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Term:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1") { echo '	<td><input type="text" name="txtTerm" value="'.$r["term"].'"/></td>'.PHP_EOL; }
				else { echo '	<td><input type="hidden" name="txtTerm" value="'.$r["term"].'"/>'.$r["term"].'</td>'.PHP_EOL; }
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Course Size:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				echo '	<td>'.formatBytes($r["csize"],2).'</td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Syllabus:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selSyllabus">'.PHP_EOL;
					$value = ($r["syllabusStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["syllabusStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["syllabusStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["syllabusStatus"]=="P") { $confStatus++; }
				} else {
					if($r["syllabusStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["syllabusStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["syllabusStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Additional Files:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selAfiles">'.PHP_EOL;
					$value = ($r["afilesStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["afilesStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["afilesStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["afilesStatus"]=="P") { $confStatus++; }
				} else {
					if($r["afilesStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["afilesStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["afilesStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Folders/Lrng. Mods:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selLrngmods">'.PHP_EOL;
					$value = ($r["lrngModsStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["lrngModsStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["lrngModsStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["lrngModsStatus"]=="P") { $confStatus++; }
				} else {
					if($r["lrngModsStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["lrngModsStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["lrngModsStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Assignments:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selAssignments">'.PHP_EOL;
					$value = ($r["assignmentsStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["assignmentsStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["assignmentsStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["assignmentsStatus"]=="P") { $confStatus++; }
				} else {
					if($r["assignmentsStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["assignmentsStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["assignmentsStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Assess/Questions:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selAques">'.PHP_EOL;
					$value = ($r["aquestionsStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["aquestionsStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["aquestionsStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["aquestionsStatus"]=="P") { $confStatus++; }
				} else {
					if($r["aquestionsStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["aquestionsStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["aquestionsStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Assess w/Images:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selAimgs">'.PHP_EOL;
					$value = ($r["aimagesStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["aimagesStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["aimagesStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["aimagesStatus"]=="P") { $confStatus++; }
				} else {
					if($r["aimagesStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["aimagesStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["aimagesStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Discussion:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selDiscussion">'.PHP_EOL;
					$value = ($r["disscussionsStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["disscussionsStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["disscussionsStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["disscussionsStatus"]=="P") { $confStatus++; }
				} else {
					if($r["disscussionsStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["disscussionsStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["disscussionsStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Gradebook:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selGradebook">'.PHP_EOL;
					$value = ($r["gradebookStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["gradebookStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["gradebookStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["gradebookStatus"]=="P") { $confStatus++; }
				} else {
					if($r["gradebookStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["gradebookStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["gradebookStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Camtasia/Studymate:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selCamtasia">'.PHP_EOL;
					$value = ($r["camtasiaStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["camtasiaStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["camtasiaStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["camtasiaStatus"]=="P") { $confStatus++; }
				} else {
					if($r["camtasiaStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["camtasiaStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["camtasiaStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Audio/Video Files:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selAVfiles">'.PHP_EOL;
					$value = ($r["avfilesStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["avfilesStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["avfilesStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["avfilesStatus"]=="P") { $confStatus++; }
				} else {
					if($r["avfilesStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["avfilesStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["avfilesStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Web-based:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selWebbased">'.PHP_EOL;
					$value = ($r["webbasedStatus"]=="P") ? 'selected="selected"' : '';
					echo '		<option value="P" '.$value.'>Pending Migration</option>'.PHP_EOL;
					$value = ($r["webbasedStatus"]=="C") ? 'selected="selected"' : '';
					echo '		<option value="C" '.$value.'>Migrated</option>'.PHP_EOL;
					$value = ($r["webbasedStatus"]=="NA") ? 'selected="selected"' : '';
					echo '		<option value="NA" '.$value.'>Not Applicable</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
					if($r["webbasedStatus"]=="P") { $confStatus++; }
				} else {
					if($r["webbasedStatus"]=="P") { echo '	<td>Pending Migration</td>'.PHP_EOL; }
					else if($r["webbasedStatus"]=="C") { echo '	<td>Migrated</td>'.PHP_EOL; }
					else if($r["webbasedStatus"]=="NA") { echo '	<td>Not Applicable</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Notes:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) { 
					echo '	<td rowspan="2"><textarea name="txtNotes" cols="45" rows="10">'.$r["notes"].'</textarea></td>'.PHP_EOL;
				} else { echo '	<td rowspan="2">'.$r["notes"].'</td>'.PHP_EOL; }
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Assigned Migrator:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1") {
					echo '	<td><select name="selMuser">'.PHP_EOL;
					$value = ($r["assignedTo"]=="0") ? 'selected="selected"' : '';
					echo '		<option value="0" '.$value.'>Not Assigned</option>'.PHP_EOL;
					$user_SQL = "SELECT * FROM user WHERE gid = 1 || gid = 2 && status != 'D' ORDER BY lname DESC";
					$userRes = $vusr->db_query($user_SQL);
					while($u=$vusr->db_fetchrow($userRes)) {
						$value = ($r["assignedTo"]==$u["uid"]) ? 'selected="selected"' : '';
						echo '		<option value="'.$u["uid"].'" '.$value.'>'.$u["lname"].', '.$u["fname"].'</option>'.PHP_EOL;
					}
					echo '	</select></td>'.PHP_EOL;
				} else {
					echo '	<td><input name="selMuser" type="hidden" value="'.$r["assignedTo"].'" />'.PHP_EOL;
					if($r["assignedTo"]==0) { echo 'Not Assigned'.PHP_EOL; }
					else {
						if($_SESSION[SESSIONPREFIX]['gid']=="3") { echo '		NMU CITE'.PHP_EOL; }
						else {
							$user_SQL = "SELECT * FROM user WHERE uid = ".$r["assignedTo"];
							$userRes = $vusr->db_query($user_SQL);
							while($u=$vusr->db_fetchrow($userRes)) { echo '		'.$u["lname"].', '.$u["fname"].PHP_EOL; }
						}
					}
					echo '	</td>'.PHP_EOL;
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">Migration Status:</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				$migStatus = $r["curStatus"];
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><select name="selMigstatus">'.PHP_EOL;
					$value = ($r["curStatus"]=="inProgress") ? 'selected="selected"' : '';
					echo '		<option value="inProgress" '.$value.'>In Progress</option>'.PHP_EOL;
					$value = ($r["curStatus"]=="waitingConfirm") ? 'selected="selected"' : '';
					echo '		<option value="waitingConfirm" '.$value.'>Awaiting Confirmation</option>'.PHP_EOL;
					if($_SESSION[SESSIONPREFIX]['gid']=="1") {
						$value = ($r["curStatus"]=="notStarted") ? 'selected="selected"' : '';
						echo '		<option value="notStarted" '.$value.'>Not Started</option>'.PHP_EOL;
					}
					$value = ($r["curStatus"]=="Complete") ? 'selected="selected"' : '';
					echo '		<option value="Complete" '.$value.'>Migration Complete</option>'.PHP_EOL;
					$value = ($r["curStatus"]=="Discard") ? 'selected="selected"' : '';
					echo '		<option value="Discard" '.$value.'>Discard</option>'.PHP_EOL;
					echo '	</select></td>'.PHP_EOL;
				} else {
					if($r["curStatus"]=="inProgress") { echo '	<td>In Progress</td>'.PHP_EOL; }
					else if($r["curStatus"]=="waitingConfirm") { echo '	<td>Awaiting Confirmation</td>'.PHP_EOL; }
					else if($r["curStatus"]=="notStarted") { echo '	<td>Not Started</td>'.PHP_EOL; }
					else if($r["curStatus"]=="Complete") { echo '	<td>Migration Complete</td>'.PHP_EOL; }
				}
				echo '  </tr>'.PHP_EOL;
				echo '  <tr>'.PHP_EOL;
				echo '	<td align="right">&nbsp;</td>'.PHP_EOL;
				echo '	<td>&nbsp;</td>'.PHP_EOL;
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '	<td><input type="submit" id="submit" name="Submit" value="Save"/>&nbsp;&nbsp;<input type="submit" id="submit" name="Submit" value="Cancel"/></td>'.PHP_EOL;
					echo '	<input name="cid" type="hidden" value="'.$r["cid"].'" />'.PHP_EOL;
				} else if($r["assignedTo"]=="0" && ($_SESSION[SESSIONPREFIX]['gid']=="1" || $_SESSION[SESSIONPREFIX]['gid']=="2")) {
					echo '	<td><input type="submit" id="submit" name="Submit" value="Assign To Me"/>&nbsp;&nbsp;<input type="submit" id="submit" name="Submit" value="Cancel"/></td>'.PHP_EOL;
					echo '	<input name="cid" type="hidden" value="'.$r["cid"].'" />'.PHP_EOL;
				} else {
					echo '	<td><input type="submit" id="submit" name="Submit" value="Cancel"/></td>'.PHP_EOL;
					echo '	<input name="cid" type="hidden" value="'.$r["cid"].'" />'.PHP_EOL;
				}
				echo '  </tr>'.PHP_EOL;
				echo '</table>'.PHP_EOL;
				//Check for contacts
				$contact_SQL = "SELECT fname,lname,toEadd,contactText,DATE_FORMAT(contactDate,'%M %D %Y at %h:%i %p') AS dfcDate FROM courseContact,user WHERE courseContact.uid = user.uid && courseContact.cid = ".$r["cid"]." ORDER BY contactDate ASC";
				$contactRes = $vusr->db_query($contact_SQL);
				$numContacts = $vusr->db_numrows($contactRes);
				if($numContacts>0) {
					echo '<div id="comments">'.PHP_EOL;
					echo '<h2>Contact History</h2>'.PHP_EOL;
					echo '<ul class="commentlist">'.PHP_EOL;
					for($k=0;$c=$vusr->db_fetchrow($contactRes);$k++) {
						if(($k%2)==0) { echo '<li class="comment_odd">'.PHP_EOL; }
						else { echo '<li class="comment_even">'.PHP_EOL; }
						echo '<div class="author"><span class="name">'.$c["fname"].' '.$c["lname"].'</span> <span class="wrote">wrote:</span></div>'.PHP_EOL;
						echo '<div class="submitdate">'.$c["dfcDate"].'</div>'.PHP_EOL;
						echo '<p>'.nl2br($c["contactText"]).'</p>'.PHP_EOL;
						echo '</li>'.PHP_EOL;
					}
					echo '</ul>'.PHP_EOL;
					echo '</div>'.PHP_EOL;
				}
				//if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid'] && $confStatus<=0) {
				if($_SESSION[SESSIONPREFIX]['gid']=="1" || $r["assignedTo"]==$_SESSION[SESSIONPREFIX]['uid']) {
					echo '<h2>Contact Instructor Of Record</h2>'.PHP_EOL;
					if(strlen($r["pnmuin"])>0) {
						echo '<div id="respond">'.PHP_EOL;
						echo '<p>To: '.$pName.' ('.$pEadd.'@nmu.edu)</p>'.PHP_EOL;
						echo '<p>From: '.EMAILFROM.' ('.EMAILADDR.')</p>'.PHP_EOL;
						echo '<p>'.PHP_EOL;
						$body .= 'Hello,'.PHP_EOL.'  We have migrated your course from WebCT to EduCat.  To complete the migration process, please review your course to verify that all content is present and the course is working properly.'.PHP_EOL.PHP_EOL;
						$body .= '1. Go to the EduCat migration site (https://'.SERVER.') and log in. You will see your migrated course(s) listed. Click the Display button to review any notes.'.PHP_EOL.PHP_EOL;
						$body .= '2. In another browser window, go to EduCat ('.MOODLEURL.').  Log in and thoroughly review your course.'.PHP_EOL.PHP_EOL;
						$body .= '3. Return to the Migration site. If there are any outstanding issues with your course, please mark the "no" radio button, note the issues, and click Submit. If there are no outstanding issues, mark the "yes" button, then click Submit.'.PHP_EOL.PHP_EOL;
						$body .= 'If you would like to meet with us in the CITE to go over anything together, please let us know. We will be glad to schedule a time.'.PHP_EOL.PHP_EOL;
						$body .= 'Thank You,'.PHP_EOL.'CITE Staff';
						if($numContacts<=0) { echo '<textarea name="comment" id="comment" cols="100%" rows="10">'.$body.'</textarea>'.PHP_EOL; }
						else { echo '<textarea name="comment" id="comment" cols="100%" rows="10"></textarea>'.PHP_EOL; }
						echo '</p>'.PHP_EOL;
						echo '<p>'.PHP_EOL;
						echo 'Send Email<input type="checkbox" name="chkEmail" id="checkbox" checked="checked"/>'.PHP_EOL;
						echo '</p>'.PHP_EOL;
						echo '<p>'.PHP_EOL;
						echo '<input name="Submit" type="submit" id="submit" value="Send Contact" />&nbsp;'.PHP_EOL;
						echo '<input name="reset" type="reset" id="reset" tabindex="5" value="Reset" />'.PHP_EOL;
						echo '</p>'.PHP_EOL;
						echo '</div>'.PHP_EOL;
						echo '	<input name="con" type="hidden" value="'.base64_encode($pEadd).'" />'.PHP_EOL;
					} else {
						echo '<p>No instructor on record!</p>'.PHP_EOL;
					}
				} else if($_SESSION[SESSIONPREFIX]['gid']=="3") {
					echo '<h2>Contact CITE</h2>'.PHP_EOL;
					echo '<div id="respond">'.PHP_EOL;
					echo '<p>To: '.EMAILFROM.' ('.EMAILADDR.')</p>'.PHP_EOL;
					echo '<p>From: '.$_SESSION[SESSIONPREFIX]['fullname'].' ('.$_SESSION[SESSIONPREFIX]['username'].'@nmu.edu)</p>'.PHP_EOL;
					echo '<p>'.PHP_EOL;
					echo '<textarea name="comment" id="comment" cols="100%" rows="10"></textarea>'.PHP_EOL;
					echo '</p>'.PHP_EOL;
					echo '<p>'.PHP_EOL;
					echo '<input name="Submit" type="submit" id="submit" value="Send Contact" />&nbsp;'.PHP_EOL;
					echo '<input name="reset" type="reset" id="reset" tabindex="5" value="Reset" />'.PHP_EOL;
					echo '</p>'.PHP_EOL;
					echo '	<input name="con" type="hidden" value="'.base64_encode("cite").'" />'.PHP_EOL;
					echo '</div>'.PHP_EOL;
				}
				echo '</form></p>'.PHP_EOL;
			}
			echo '</div>'.PHP_EOL;
			if(($_SESSION[SESSIONPREFIX]['gid']=="3" || $_SESSION[SESSIONPREFIX]['gid']=="1") && $migStatus=="waitingConfirm") {
				echo '<div id="column">'.PHP_EOL;
				echo '<div class="holder">'.PHP_EOL;
				echo '<form name="frmComplete" method="post">'.PHP_EOL;
				echo '<h2 class="title"><strong>Completion Notification</strong></h2>'.PHP_EOL;
				if(isset($_SESSION[SESSIONPREFIX]['approvalErr']) && strlen($_SESSION[SESSIONPREFIX]['approvalErr'])>0) {
					echo '<p><font color="red"><strong>Error:</font> '.$_SESSION[SESSIONPREFIX]['approvalErr'].'</strong></p>'.PHP_EOL;
					unset($_SESSION[SESSIONPREFIX]['approvalErr']);
				}
				echo '<p>Our records indicate that this course is ready for use. Please review this course in EduCat and make sure everything is working properly. Once you have decided that the course is working properly check the box below indicating the course migration is complete. If the course is not working properly, click the box below indicating that some problems do exist with the course that need to be addressed before migration can be complete.</p>'.PHP_EOL;
				echo '<p><input type="radio" name="rdoApproval" id="radio" value="1" />Yes, the migration is complete.<br /><input type="radio" name="rdoApproval" id="radio" value="0" />No, some items need to be addressed.</p>'.PHP_EOL;
				echo '<p><textarea name="txtNotes" cols="40" rows="7"></textarea></p>'.PHP_EOL;
				echo '<input name="compSubmit" type="submit" id="submit" value="Submit" />&nbsp;'.PHP_EOL;
				echo '<input name="cid" type="hidden" value="'.$cid.'" />'.PHP_EOL;
				echo '</form>'.PHP_EOL;
				echo '</div>'.PHP_EOL;
			} else if($_SESSION[SESSIONPREFIX]['gid']=="3" && $migStatus=="Complete") {
				echo '<div id="column">'.PHP_EOL;
				echo '<div class="holder">'.PHP_EOL;
				echo '<h2 class="title"><strong>Completion Notification</strong></h2>'.PHP_EOL;
				echo '<p>Our records indicate that this course is ready to use in the EduCat system. Please let us know if you experience any problems.<br /><br />Thank you!</p>'.PHP_EOL;
				echo '</div>'.PHP_EOL;
			}
			echo '</div>'.PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
?>