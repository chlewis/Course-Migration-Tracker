<?PHP
	function saveFaqChanges($faqid=0,$faqSubject="",$faqText="",$faqStatus="") {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "UPDATE migFAQ SET faqSubject = '".mysql_real_escape_string($faqSubject)."',faqText = '".mysql_real_escape_string($faqText)."',vStatus = '".mysql_real_escape_string($faqStatus)."' WHERE faqid = ".mysql_real_escape_string($faqid)." LIMIT 1";
		$result = $vusr->db_query($query);
		$faqid = $vusr->db_insertid();
		$vusr->db_close();
		unset($vusr);
		showFAQsToEdit();
	}

	function editFAQ($faqid=0) {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT faqid,faqSubject,faqText,vStatus FROM migFAQ WHERE faqid = ".mysql_real_escape_string($faqid)." LIMIT 1";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<div class="wrapper col4">'.PHP_EOL;
			echo '<div id="container">'.PHP_EOL;
			echo '<div id="content">'.PHP_EOL;
			for($k=0;$r=$vusr->db_fetchrow($result);$k++) {
				echo '<form name="frmAddfaq" method="post" />'.PHP_EOL;
				echo '<h2>Edit FAQ</h2>'.PHP_EOL;
				echo '<div id="respond">'.PHP_EOL;
				echo '<p>'.PHP_EOL;
				echo '<input type="text" name="name" id="name" value="'.$r["faqSubject"].'" size="25" />'.PHP_EOL;
				echo '<label for="name"><small>Subject (required)</small></label>'.PHP_EOL;
				echo '</p>'.PHP_EOL;
				echo '<p>'.PHP_EOL;
				echo '<textarea name="comment" id="comment" cols="100%" rows="10">'.$r["faqSubject"].'</textarea>'.PHP_EOL;
				echo '</p>'.PHP_EOL;
				echo '<p>'.PHP_EOL;
				echo '<select name="selStatus">'.PHP_EOL;
				$value = ($r["vStatus"]=="Yes") ? 'selected="selected"' : '';
				echo '	<option value="Yes" '.$value.'>Yes</option>'.PHP_EOL;
				$value = ($r["vStatus"]=="No") ? 'selected="selected"' : '';
				echo '	<option value="No" '.$value.'>No</option>'.PHP_EOL;
				echo '</select>'.PHP_EOL;
				echo '<label for="name"><small>FAQ Visible? (required)</small></label>'.PHP_EOL;
				echo '</p>'.PHP_EOL;
				echo '<p>'.PHP_EOL;
				echo '<input name="Submit" type="submit" id="submit" value="Save Changes" />&nbsp;'.PHP_EOL;
				echo '<input name="faqSubmit" type="submit" id="submit" value="Cancel" />&nbsp;'.PHP_EOL;
				echo '</p>'.PHP_EOL;
				echo '</div>'.PHP_EOL;
				echo '<input type="hidden" name="f" value="'.$r["faqid"].'">'.PHP_EOL;
				echo '</form>'.PHP_EOL;
			}
			echo '</div>'.PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
	
	function addFAQ($subject="",$faqBody="") {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "INSERT INTO migFAQ(faqSubject,faqText,vStatus,hitCount,dateAdded) VALUES('".mysql_real_escape_string($subject)."','".mysql_real_escape_string($faqBody)."','Yes',0,NOW())";
		$result = $vusr->db_query($query);
		$faqid = $vusr->db_insertid();
		$vusr->db_close();
		unset($vusr);
		showFAQsToEdit();
	}

	function showFAQsToEdit() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT faqid,faqSubject,hitCount,DATE_FORMAT(dateAdded ,'%m/%d/%y at %r') AS dfcDate FROM migFAQ WHERE vStatus = 'Yes' ORDER BY dateAdded DESC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<div class="wrapper col4">'.PHP_EOL;
			echo '<div id="container">'.PHP_EOL;
			echo '<div id="content">'.PHP_EOL;
			echo '<table summary="Conversion Courses" cellpadding="0" cellspacing="0">'.PHP_EOL;
			echo '<thead>'.PHP_EOL;
			echo '  <tr>'.PHP_EOL;
			echo '	<th>Subject</th>'.PHP_EOL;
			echo '	<th>Hit Count</th>'.PHP_EOL;
			echo '	<th>Date Added</th>'.PHP_EOL;
			echo '	<th>*</th>'.PHP_EOL;
			echo '  </tr>'.PHP_EOL;
			echo '</thead>'.PHP_EOL;
			echo '<tbody>'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				$trclass = (($i%2)==0) ? "light" : "dark";
				echo '  <tr class="'.$trclass.'">'.PHP_EOL;
				echo '	<td>'.$r["faqSubject"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["hitCount"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["dfcDate"].'</td>'.PHP_EOL;
				echo '	<td><form name="frmList" method="post"><input type="Submit" name="Submit" value="Edit"><input type="hidden" name="f" value="'.$r["faqid"].'"></form></td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
			}
			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
			echo '<h2>Add FAQ</h2>'.PHP_EOL;
			echo '<form name="frmAddfaq" method="post" />'.PHP_EOL;
			echo '<div id="respond">'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<input type="text" name="name" id="name" value="" size="25" />'.PHP_EOL;
			echo '<label for="name"><small>Subject (required)</small></label>'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<textarea name="comment" id="comment" cols="100%" rows="10"></textarea>'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<input name="Submit" type="submit" id="submit" value="Add FAQ" />&nbsp;'.PHP_EOL;
			echo '<input name="reset" type="reset" id="reset" tabindex="5" value="Reset" />'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</form>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
	
	function showAllFAQs() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT faqSubject,faqText,DATE_FORMAT(dateAdded ,'%M %D %Y at %h:%i %p') AS dfcDate FROM migFAQ WHERE vStatus = 'Yes' ORDER BY hitCount DESC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<div class="wrapper col4">'.PHP_EOL;
			echo '<div id="container">'.PHP_EOL;
			echo '<div id="content">'.PHP_EOL;
			echo '<div id="comments">'.PHP_EOL;
			for($k=0;$c=$vusr->db_fetchrow($result);$k++) {
				echo '<h2>Migration FAQ</h2>'.PHP_EOL;
				echo '<ul class="commentlist">'.PHP_EOL;
				if(($k%2)==0) { echo '<li class="comment_odd">'.PHP_EOL; }
				else { echo '<li class="comment_even">'.PHP_EOL; }
				echo '<div class="author"><span class="name">NMU CITE</span> <span class="wrote">wrote:</span></div>'.PHP_EOL;
				echo '<div class="submitdate">'.$c["dfcDate"].'</div>'.PHP_EOL;
				echo '<p><strong>'.nl2br($c["faqSubject"]).'</strong></p>'.PHP_EOL;
				echo '<p>'.nl2br($c["faqText"]).'</p>'.PHP_EOL;
				echo '</li>'.PHP_EOL;
				echo '</ul>'.PHP_EOL;
			}
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
	
	function showFAQ($faqID=0) {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT faqSubject,faqText,DATE_FORMAT(dateAdded ,'%M %D %Y at %h:%i %p') AS dfcDate FROM migFAQ WHERE faqid = ".mysql_real_escape_string($faqID)." && vStatus = 'Yes' LIMIT 1";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<div class="wrapper col4">'.PHP_EOL;
			echo '<div id="container">'.PHP_EOL;
			echo '<div id="content">'.PHP_EOL;
			echo '<div id="comments">'.PHP_EOL;
			for($k=0;$c=$vusr->db_fetchrow($result);$k++) {
				echo '<h2>Migration FAQ</h2>'.PHP_EOL;
				echo '<ul class="commentlist">'.PHP_EOL;
				$assign_SQL = "UPDATE migFAQ SET hitCount = hitCount+1 WHERE faqid = ".mysql_real_escape_string($faqID)." LIMIT 1";
				$assRes = $vusr->db_query($assign_SQL);
				if(($k%2)==0) { echo '<li class="comment_odd">'.PHP_EOL; }
				else { echo '<li class="comment_even">'.PHP_EOL; }
				echo '<div class="author"><span class="name">NMU CITE</span> <span class="wrote">wrote:</span></div>'.PHP_EOL;
				echo '<div class="submitdate">'.$c["dfcDate"].'</div>'.PHP_EOL;
				echo '<p><strong>'.nl2br($c["faqSubject"]).'</strong></p>'.PHP_EOL;
				echo '<p>'.nl2br($c["faqText"]).'</p>'.PHP_EOL;
				echo '</li>'.PHP_EOL;
				echo '</ul>'.PHP_EOL;
			}
			echo '<p><a href="?s='.md5("showAllFAQs").'">Click Here</a> to view all FAQ\'s</p>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}

?>