<?PHP
	function doLogin($username="",$pass="") {
		global $ldap_server;
		$_SESSION[SESSIONPREFIX]['valid'] = false;
		$_SESSION[SESSIONPREFIX]['username'] = strtolower($username);
		if(strlen($_SESSION[SESSIONPREFIX]['username'])>0 && strlen($pass)>0) {
			if(USELDAP) {
				$ds=ldap_connect($ldap_server[0]);
				$r=ldap_bind($ds,"uid=".$_SESSION[SESSIONPREFIX]['username'].",ou=People,dc=nmu,dc=edu",$pass);
				if (!$r) {
					$lderr = ldap_errno($ds);
					if($lderr==-1) {
						for($i=1;$i<count($ldap_server);$i++) {
							$ds=ldap_connect($ldap_server[$i]);
							$r=ldap_bind($ds,"uid=".$_SESSION[SESSIONPREFIX]['username'].",ou=People,dc=nmu,dc=edu",$pass);
							$lderr = ldap_errno($ds);
							if($r) { break; }
						}
					}
				}
				if($lderr==49) {
					$_SESSION[SESSIONPREFIX]['loginErr'] = "Invalid Username and/or Password!";
					if($_SESSION[SESSIONPREFIX]['invalid_pwdct']<8) { $_SESSION[SESSIONPREFIX]['invalid_pwdct'] = $_SESSION[SESSIONPREFIX]['invalid_pwdct']+2; }
					else { $_SESSION[SESSIONPREFIX]['invalid_pwdct'] = $_SESSION[SESSIONPREFIX]['invalid_pwdct']+7; }
				} else if($r) {
					ldap_close($ds);
					unset($_SESSION[SESSIONPREFIX]['invalid_pwdct']);
					session_regenerate_id();
					$_SESSION[SESSIONPREFIX]['auth'] = true;
					$_SESSION[SESSIONPREFIX]['valid'] = true;
				} else { 
					$_SESSION[SESSIONPREFIX]['loginErr'] = "A serious error has occurred.  Please try to login again later."; 
				}
				ldap_close($ds);
			} else {
				$vusr = new db;
				$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
				$auth_SQL = "SELECT uid FROM user WHERE LOWER(ename) = '".mysql_real_escape_string(strtolower($_SESSION[SESSIONPREFIX]['username']))."' && passwd = PASSWORD('".mysql_real_escape_string($pass)."') LIMIT 1";
				$result = $vusr->db_query($auth_SQL);
				if($vusr->db_numrows($result)>0) {
					unset($_SESSION[SESSIONPREFIX]['invalid_pwdct']);
					session_regenerate_id();
					$_SESSION[SESSIONPREFIX]['auth'] = true;
					$_SESSION[SESSIONPREFIX]['valid'] = true;				
				}
				$vusr->db_close();
				unset($vusr);
			}
			//set all of the user stuff here
			if($_SESSION[SESSIONPREFIX]['valid']) {
				//if($_SESSION[SESSIONPREFIX]['username']=="dumbass") { $_SESSION[SESSIONPREFIX]['username'] = "tferrari"; }
				$vusr = new db;
				$vusr->db_connect($_SESSION[SESSIONPREFIX]['dbserver'], $_SESSION[SESSIONPREFIX]['dbuser'], $_SESSION[SESSIONPREFIX]['dbpass'], $_SESSION[SESSIONPREFIX]['dbase'], "mysql");
				$query = "SELECT * FROM user,groups WHERE user.gid = groups.gid && user.ename = '".mysql_real_escape_string($_SESSION[SESSIONPREFIX]['username'])."' LIMIT 1";
				$result = $vusr->db_query($query);
				if($vusr->db_numrows($result)>0) {
					while($r=$vusr->db_fetchrow($result)) {
						$_SESSION[SESSIONPREFIX]['valid'] = true;
						$_SESSION[SESSIONPREFIX]['fname'] = ucfirst(strtolower($r["fname"]));
						$_SESSION[SESSIONPREFIX]['fullname'] = $r["fname"]." ";
						if(strlen($r["midinit"])>0) { $_SESSION[SESSIONPREFIX]['fullname'] .= $r["midinit"]." "; }
						$_SESSION[SESSIONPREFIX]['fullname'] .= $r["lname"];
						$_SESSION[SESSIONPREFIX]['nmuin'] = $r["nmuin"];
						$_SESSION[SESSIONPREFIX]['uid'] = $r["uid"];
						$_SESSION[SESSIONPREFIX]['gid'] = $r["gid"];
						$_SESSION[SESSIONPREFIX]['status'] = $r["status"];
						$_SESSION[SESSIONPREFIX]['private_key'] = md5(date('Ymd').$_SESSION[SESSIONPREFIX]['uid'].$_SESSION[SESSIONPREFIX]['nmuin'].session_id());
					}
					if($_SESSION[SESSIONPREFIX]['valid']==true && $_SESSION[SESSIONPREFIX]['status']!=="D") {
						$updt_sql = "update user set last_login = NOW(), last_ip = '".$_SERVER["REMOTE_ADDR"]."', sessid = '".session_id()."' where uid = ".$_SESSION[SESSIONPREFIX]['uid']." LIMIT 1";
						$result = $vusr->db_query($updt_sql);
					} else {
						$_SESSION[SESSIONPREFIX]['loginErr'] = "Your account has been disabled.";
						$_SESSION[SESSIONPREFIX]['valid'] = false;
						$_SESSION[SESSIONPREFIX]['auth'] = false;
					}
				} 
				$vusr->db_close();
				unset($vusr);
				if($_SESSION[SESSIONPREFIX]['valid']) { header("Location: https://".SERVER); }
			} else {
				//this could be an instrucotr or someone without access.  See if they are an instructor first.
				$_SESSION[SESSIONPREFIX]['loginErr'] = "Invalid Username and/or Password!";
				if($_SESSION[SESSIONPREFIX]['invalid_pwdct']<8) { $_SESSION[SESSIONPREFIX]['invalid_pwdct'] = $_SESSION[SESSIONPREFIX]['invalid_pwdct']+2; }
				else { $_SESSION[SESSIONPREFIX]['invalid_pwdct'] = $_SESSION[SESSIONPREFIX]['invalid_pwdct']+7; }
			}
			
					
					/*
					//this could be an instrucotr or someone without access.  See if they are an instructor first.
					$cAct = false;
					//get their nmuin first
					$oraDB = new db;
					$oraConn = $oraDB->db_connect("", $_SESSION[SESSIONPREFIX]['orauser'], $_SESSION[SESSIONPREFIX]['orapass'], "", "oracle");
					$sql = "SELECT NMUID,EMAIL_ID,LAST_NAME,FIRST_NAME,MIDDLE_INITIAL FROM HELPDESK.TLC_CALLERS WHERE LOWER(EMAIL_ID) = '".$_SESSION[SESSIONPREFIX]['username']."'";
					$oraRes = $oraDB->db_query($sql);
					if($oraDB->db_numrows($oraRes)>0) {
						while($ora=$oraDB->db_fetchrow($oraRes)) {
							$nmuin = $ora["nmuid"];
							$eadd = $ora["email_id"];
							$fname = $ora["first_name"];
							$lname = $ora["last_name"];
							$mname = $ora["middle_initial"];
						}
						//see if they have any courses
						$course_SQL = "SELECT * FROM courses WHERE pnmuin = '".trim($nmuin)."'";
						$courseRes = $vusr->db_query($course_SQL);
						if($vusr->db_numrows($courseRes)>0) {
							//since they have courses they must be an instructor so make them an account and log them in.
							$adduser_SQL = "INSERT INTO user(fname,lname,midinit,nmuin,ename,gid,date_create,status) VALUES('".ucfirst(strtolower(trim($fname)))."','".ucfirst(strtolower(trim($lname)))."','".ucfirst(strtolower(trim($mname)))."','".trim($nmuin)."','".strtolower(trim($eadd))."',3,NOW(),'F')";
							$adduserRes = $vusr->db_query($adduser_SQL);
							$_SESSION[SESSIONPREFIX]['username'] = strtolower($eadd);
							$query = "SELECT * FROM user,groups WHERE user.gid = groups.gid && user.ename = '".mysql_real_escape_string($_SESSION[SESSIONPREFIX]['username'])."' LIMIT 1";
							$result = $vusr->db_query($query);
							if($vusr->db_numrows($result)>0) {
								while($r=$vusr->db_fetchrow($result)) {
									$_SESSION[SESSIONPREFIX]['valid'] = true;
									$_SESSION[SESSIONPREFIX]['fname'] = ucfirst(strtolower($r["fname"]));
									$_SESSION[SESSIONPREFIX]['fullname'] = $r["fname"]." ";
									if(strlen($r["midinit"])>0) { $_SESSION[SESSIONPREFIX]['fullname'] .= $r["midinit"]." "; }
									$_SESSION[SESSIONPREFIX]['fullname'] .= $r["lname"];
									$_SESSION[SESSIONPREFIX]['nmuin'] = $r["nmuin"];
									$_SESSION[SESSIONPREFIX]['uid'] = $r["uid"];
									$_SESSION[SESSIONPREFIX]['gid'] = $r["gid"];
									$_SESSION[SESSIONPREFIX]['status'] = $r["status"];
									$_SESSION[SESSIONPREFIX]['private_key'] = md5(date('Ymd').$_SESSION[SESSIONPREFIX]['uid'].$_SESSION[SESSIONPREFIX]['nmuin'].session_id());
								}
								if($_SESSION[SESSIONPREFIX]['valid']==true && $_SESSION[SESSIONPREFIX]['status']!=="D") {
									$updt_sql = "update user set last_login = NOW(), last_ip = '".$_SERVER["REMOTE_ADDR"]."', sessid = '".session_id()."' where uid = ".$_SESSION[SESSIONPREFIX]['uid']." LIMIT 1";
									$result = $vusr->db_query($updt_sql);
								} else {
									$_SESSION[SESSIONPREFIX]['loginErr'] = "Your account has been disabled.";
									$_SESSION[SESSIONPREFIX]['valid'] = false;
									$_SESSION[SESSIONPREFIX]['auth'] = false;
								}
							}
						}
					}
					$oraDB->db_close();
					unset($oraDB);*/
				//}
		} else {
			$_SESSION[SESSIONPREFIX]['loginErr'] = "please provide a username and a password.";
		}
		sleep($_SESSION[SESSIONPREFIX]['invalid_pwdct']);
	}
?>