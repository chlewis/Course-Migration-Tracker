<?PHP
	function addUser($uname="",$grp=0,$status="D") {
		echo "Did not add: ".$uname." <br />";
		showUsersToEdit();
	}
	
	function showUsersToEdit() {
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT uid,fname,lname,ename,DATE_FORMAT(last_login ,'%m/%d/%y at %r') AS dfllogin,gdesc FROM user,groups WHERE user.gid = groups.gid && status != 'D' ORDER BY lname ASC";
		$result = $vusr->db_query($query);
		if($vusr->db_numrows($result)>0) {
			echo '<div class="wrapper col4">'.PHP_EOL;
			echo '<div id="container">'.PHP_EOL;
			echo '<div id="content">'.PHP_EOL;
			echo '<table summary="Conversion Courses" cellpadding="0" cellspacing="0">'.PHP_EOL;
			echo '<thead>'.PHP_EOL;
			echo '  <tr>'.PHP_EOL;
			echo '	<th>Name</th>'.PHP_EOL;
			echo '	<th>Group</th>'.PHP_EOL;
			echo '	<th>Last Login</th>'.PHP_EOL;
			//echo '	<th>*</th>'.PHP_EOL;
			echo '  </tr>'.PHP_EOL;
			echo '</thead>'.PHP_EOL;
			echo '<tbody>'.PHP_EOL;
			for($i=0;$r=$vusr->db_fetchrow($result);$i++) {
				$trclass = (($i%2)==0) ? "light" : "dark";
				echo '  <tr class="'.$trclass.'">'.PHP_EOL;
				echo '	<td>'.$r["lname"].', '.$r["fname"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["gdesc"].'</td>'.PHP_EOL;
				echo '	<td>'.$r["dfllogin"].'</td>'.PHP_EOL;
				//echo '	<td><form name="frmList" method="post"><input type="Submit" name="usrSubmit" value="Edit"><input type="hidden" name="u" value="'.$r["uid"].'"></form></td>'.PHP_EOL;
				echo '  </tr>'.PHP_EOL;
			}
			echo '</tbody>'.PHP_EOL;
			echo '</table>'.PHP_EOL;
			echo '<h2>Add User</h2>'.PHP_EOL;
			/*echo '<form name="frmAddfaq" method="post" />'.PHP_EOL;
			echo '<div id="respond">'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<input type="text" name="uname" id="name" value="" size="25" />'.PHP_EOL;
			echo '<label for="name"><small>Username (required)</small></label>'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<select name="selGroup">'.PHP_EOL;
			echo '	<option value="1">Administrator</option>'.PHP_EOL;
			echo '	<option value="2" selected="selected">Converter</option>'.PHP_EOL;
			echo '	<option value="3">Course Owner</option>'.PHP_EOL;
			echo '</select>'.PHP_EOL;
			echo '<label for="name"><small>Group (required)</small></label>'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<select name="selStatus">'.PHP_EOL;
			echo '	<option value="S">Staff</option>'.PHP_EOL;
			echo '	<option value="St" selected="selected">Student</option>'.PHP_EOL;
			echo '	<option value="F">Faculty</option>'.PHP_EOL;
			echo '</select>'.PHP_EOL;
			echo '<label for="name"><small>Status (required)</small></label>'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '<p>'.PHP_EOL;
			echo '<input name="Submit" type="submit" id="submit" value="Add User" />&nbsp;'.PHP_EOL;
			echo '</p>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</form>'.PHP_EOL;*/
			echo 'Please add user through the Database. This function will be fixed very soon.'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '<div class="clear"></div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
			echo '</div>'.PHP_EOL;
		}
		$vusr->db_close();
		unset($vusr);
	}
?>