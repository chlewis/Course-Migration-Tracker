<?PHP
	function frontPage_NotLoggedIn() {
		echo '<div class="wrapper col4">'.PHP_EOL;
		echo '  <div id="container">'.PHP_EOL;
		echo '    <div id="services">'.PHP_EOL;
		echo '      <ul>'.PHP_EOL;
		echo '        <li>'.PHP_EOL;
		echo '          <div class="imgholder"><img src="elements/images/loginHdr.jpg" alt="Login" /></div>'.PHP_EOL;
		echo '          <h2>Login</h2>'.PHP_EOL;
		echo '          <p>'.PHP_EOL;
		echo '          <div id="respond">'.PHP_EOL;
		echo '          	<p>Enter your NMU username and password</p>'.PHP_EOL;
		echo '            <form action="#" method="post">'.PHP_EOL;
		echo '              <p>'.PHP_EOL;
		echo '              	<label for="name"><small>Username:</small></label>'.PHP_EOL;
		echo '                <input type="text" name="uname" id="name" value="" size="22" />'.PHP_EOL;
		echo '              </p>'.PHP_EOL;
		echo '              <p>'.PHP_EOL;
		echo '              	<label for="passwd"><small>Password:</small>&nbsp;</label>'.PHP_EOL;
		echo '                <input type="password" name="passwd" id="passwd" value="" size="22" />'.PHP_EOL;
		echo '              </p>'.PHP_EOL;
		echo '              <p>&nbsp;</p>'.PHP_EOL;
		echo '              <p>'.PHP_EOL;
		echo '                <input name="submit" type="submit" id="submit" value="Login" />'.PHP_EOL;
		echo '              </p>'.PHP_EOL;
		echo '            </form>'.PHP_EOL;
		echo '           </div>'.PHP_EOL;
		echo '           <p class="readmore">&nbsp;</p>'.PHP_EOL;
		echo '		</p>'.PHP_EOL;
		echo '        </li>'.PHP_EOL;
		echo '        <li>'.PHP_EOL;
		echo '          <div class="imgholder"><img src="elements/images/mstatusHdr.jpg" alt="Migration Status" /></div>'.PHP_EOL;
		echo '          <h2>Overall Migration Status</h2>'.PHP_EOL;
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		$query = "SELECT COUNT(*) AS ctotal FROM courseStatus WHERE curStatus != 'Discard'";
		$result = $vusr->db_query($query);
		while($u=$vusr->db_fetchrow($result)) { $courseTotal = $u["ctotal"]; }
		$query = "SELECT COUNT(*) AS convtotal FROM courseStatus WHERE curStatus = 'Complete'";
		$result = $vusr->db_query($query);
		while($u=$vusr->db_fetchrow($result)) { $convTotal = $u["convtotal"]; }
		saveImage('http://chart.apis.google.com/chart?chs=290x160&cht=gom&chd=t:'.number_format((($convTotal/$courseTotal)*100),0),DIR.'/elements/images/','educat_coversion_goal.png');
		echo '          <p><img src="elements/images/educat_coversion_goal.png" /></p>'.PHP_EOL;
		echo '          <p>So far, '.$convTotal.' out of '.$courseTotal.' courses have been converted!</p>'.PHP_EOL;
		echo '        </li>'.PHP_EOL;
		echo '        <li class="last">'.PHP_EOL;
		echo '          <div class="imgholder"><img src="elements/images/faqsHdr.jpg" alt="Frequently Asked Questions" /></div>'.PHP_EOL;
		echo '          <h2>Common Questions</h2>'.PHP_EOL;
		echo '          <p>Here you will find the answers to some of the most common questions.</p>'.PHP_EOL;
		echo '          <p>'.PHP_EOL;
		echo '              <ul>'.PHP_EOL;
		$query = "SELECT faqSubject,faqid FROM migFAQ WHERE vStatus = 'Yes' ORDER BY hitCount DESC LIMIT 5";
		$result = $vusr->db_query($query);
		for($i=0;$r=$vusr->db_fetchrow($result);$i++) { 
			echo '                <li><a href="?s='.md5("showFAQ").'&f='.base64_encode($r["faqid"]).'">&raquo; '.$r["faqSubject"].'</a></li>'.PHP_EOL;
		}
		echo '              </ul>'.PHP_EOL;
		echo '          </p>'.PHP_EOL;
		echo '          <p>&nbsp;</p>'.PHP_EOL;
		echo '          <p class="readmore"><a href="?s='.md5("showAllFAQs").'">View all Questions &raquo;</a></p>'.PHP_EOL;
		echo '        </li>'.PHP_EOL;
		echo '      </ul>'.PHP_EOL;
		echo '      <br class="clear" />'.PHP_EOL;
		echo '    </div>'.PHP_EOL;
		echo '    <br class="clear" />'.PHP_EOL;
		echo '  </div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}

	function mainHeader() {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.PHP_EOL;
		echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="EN" lang="EN" dir="ltr">'.PHP_EOL;
		echo '<head profile="http://gmpg.org/xfn/11">'.PHP_EOL;
		echo '<title>EduCat Migration System</title>'.PHP_EOL;
		echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />'.PHP_EOL;
		echo '<meta http-equiv="imagetoolbar" content="no" />'.PHP_EOL;
		echo '<link rel="stylesheet" href="elements/css/layout.css" type="text/css" />'.PHP_EOL;
		echo '<script src="elements/js/jquery-1.4.2.min.js" type="text/javascript"></script>'.PHP_EOL;
		echo '<script src="elements/js/jquery.tablesorter.min.js" type="text/javascript"></script>'.PHP_EOL;
		echo '<script src="elements/js/jquery.tablesorter.pager.js" type="text/javascript"></script>'.PHP_EOL;
		echo '</head>'.PHP_EOL;
		echo '<body id="top">'.PHP_EOL;
		echo '<div class="wrapper col1">'.PHP_EOL;
		echo '  <div id="header">'.PHP_EOL;
		echo '	<div id="logo">'.PHP_EOL;
		echo '	  <h1><a href="#">NMU EduCat Migration</a></h1>'.PHP_EOL;
		echo '	  <p>Course Migration</p>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '	<div id="info">'.PHP_EOL;
		echo '	  <ul>'.PHP_EOL;
		echo '		<li>'.date("F jS, Y").'</li>'.PHP_EOL;
		echo '	  </ul>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '	<br class="clear" />'.PHP_EOL;
		echo '  </div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '<div class="wrapper col2">'.PHP_EOL;
		echo '  <div id="topbar">'.PHP_EOL;
		echo '	<div id="topnav">'.PHP_EOL;
		echo '	  <ul>'.PHP_EOL;
		echo '		<li class="active"><a href="index.php">Home</a></li>'.PHP_EOL;
		if(checkUser()) { 
			if($_SESSION[SESSIONPREFIX]['gid']=="1") {
				echo '		<li><a href="?s='.md5("edituser".session_id()).'">Add/Edit Users</a></li>'.PHP_EOL;
				echo '		<li><a href="?s='.md5("editfaq".session_id()).'">Add/Edit FAQs</a></li>'.PHP_EOL;
				echo '		<li><a href="?s='.md5("addcourse".session_id()).'">Add Course</a></li>'.PHP_EOL;
			}
			echo '		<li><a href="?s='.md5("logout".session_id()).'">Logout</a></li>'.PHP_EOL;
		}
		echo '	  </ul>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '	<br class="clear" />'.PHP_EOL;
		echo '  </div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '<div class="wrapper col3">'.PHP_EOL;
		echo '  <div id="intro">'.PHP_EOL;
		if(!checkUser()) {
			echo '	<div class="fl_left"><img src="elements/images/migration.jpg" alt="" /></div>'.PHP_EOL;
			echo '	<div class="fl_right">'.PHP_EOL;
			echo '	  <h2>Course Migration</h2>'.PHP_EOL;
			echo '	  <p>You can put something in here!</p>'.PHP_EOL;
			echo '	</div>'.PHP_EOL;
		}
		echo '	<br class="clear" />'.PHP_EOL;
		echo '  </div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
	}
	
	function mainFooter() {
		echo '<div class="wrapper col5">'.PHP_EOL;
		echo '  <div id="footer">'.PHP_EOL;
		echo '	<div class="footbox">'.PHP_EOL;
		echo '	  <h2>Blogroll</h2>'.PHP_EOL;
		echo '	  <ul>'.PHP_EOL;
		echo '		<li><a href="#">&raquo; Use PHP to loop through the blog.</a></li>'.PHP_EOL; $ctr++; }
		echo '	  </ul>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '	<div class="footbox last">'.PHP_EOL;
		echo '	  <h2>Contact Information</h2>'.PHP_EOL;
		echo '	  <address>'.PHP_EOL;
		echo '	  Email Address: you@yourschool.edu'.PHP_EOL;
		echo '	  </address>'.PHP_EOL;
		echo '	  <address>'.PHP_EOL;
		echo '	  Phone: (555) 555-5555'.PHP_EOL;
		echo '	  </address>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '	<br class="clear" />'.PHP_EOL;
		echo '  </div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '<div class="wrapper col6">'.PHP_EOL;
		echo '  <div id="copyright">'.PHP_EOL;
		echo '	<p class="fl_left">Copyright &copy; '.date("Y").' - Your Organization.</p>'.PHP_EOL;
		echo '	<br class="clear" />'.PHP_EOL;
		echo '  </div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '</body>'.PHP_EOL;
		echo '</html>'.PHP_EOL;
	}
	
	function saveImage($chart_url,$path,$file_name){
		if(!file_exists($path.$file_name) || (md5_file($path.$file_name) != md5_file($chart_url))) {
			file_put_contents($path.$file_name,file_get_contents($chart_url));
		}
	}
	
	function checkUser() {
		/* See if the logged in user is valid and if they have an account */
		$rtnflg = false;
		$vusr = new db;
		$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
		if($_SESSION[SESSIONPREFIX]['valid']==true) {
			$query = "SELECT * FROM user WHERE sessid = '".session_id()."' && uid = ".$_SESSION[SESSIONPREFIX]['uid']."";
			$result = $vusr->db_query($query);
			if($vusr->db_numrows($result)>0) {
				$last_line = exec('ls /tmp | grep sess_', $retval);
				$final = array();
				for($i=0;$i<count($retval);$i++) { $final[$i] = str_replace("sess_","",$retval[$i]); }
				if(in_array(session_id(),$final)) { $rtnflg = true; }
			}
			if($_SESSION[SESSIONPREFIX]['private_key']==md5(date('Ymd').$_SESSION[SESSIONPREFIX]['uid'].$_SESSION[SESSIONPREFIX]['nmuin'].session_id())) { $rtnflg = true; }
		}
		$vusr->db_close();
		unset($vusr);
		if(!$rtnflg) { 
			$_SESSION[SESSIONPREFIX]['ldap_auth'] = false;
			$_SESSION[SESSIONPREFIX]['valid'] = false;
		}
		return $_SESSION[SESSIONPREFIX]['valid'];
	}
	
	function formatBytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
		$bytes = max(($bytes*1024), 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
		$bytes /= pow(1024, $pow); 
		return round($bytes, $precision) . ' ' . $units[$pow]; 
	} 

?>