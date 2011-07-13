<?PHP
	session_start(); 
	require_once(getcwd()."/elements/functions/lib.php"); 
	if(!$_SERVER['HTTPS'] && USELSSL) { header("Location: https://".SERVER."?".$_SERVER["QUERY_STRING"]); }
	
	mainHeader();
	if(checkUser()) {
		if($_POST["Submit"]=="Display") {
			displayCourse($_POST["c"]);
		} else if($_POST["compSubmit"]=="Submit") {
			confirmCourse($_POST["cid"],$_POST["rdoApproval"],$_POST["txtNotes"]);
		} else if($_POST["Submit"]=="Add Course") {
			addCourse_addIt($_POST["txtPrefix"],$_POST["txtListing"],$_POST["txtInmuin"],$_POST["txtTerm"],$_POST["txtSize"],$_POST["selSyllabus"],$_POST["selAfiles"],$_POST["selLrngmods"],$_POST["selAssignments"],$_POST["selAques"],$_POST["selAimgs"],$_POST["selDiscussion"],$_POST["selGradebook"],$_POST["selCamtasia"],$_POST["selAVfiles"],$_POST["selWebbased"],$_POST["txtNotes"]);
		} else if($_POST["Submit"]=="Save") {
			saveCourse($_POST["cid"],$_POST["txtPrefix"],$_POST["txtListing"],$_POST["txtInmuin"],$_POST["txtTerm"],$_POST["selSyllabus"],$_POST["selAfiles"],$_POST["selLrngmods"],$_POST["selAssignments"],$_POST["selAques"],$_POST["selAimgs"],$_POST["selDiscussion"],$_POST["selGradebook"],$_POST["selCamtasia"],$_POST["selAVfiles"],$_POST["selWebbased"],$_POST["txtNotes"],$_POST["selMuser"],$_POST["selMigstatus"]);
		} else if($_POST["Submit"]=="Send Contact") {
			contactInstructor($_POST["cid"],$_POST["con"],$_POST["comment"],$_POST["chkEmail"]);
		} else if($_POST["Submit"]=="Add User") {
			addUser($_POST["uname"],$_POST["selGroup"],$_POST["selStatus"]);
		} else if($_POST["Submit"]=="Save Changes") {
			saveFaqChanges($_POST["f"],$_POST["name"],$_POST["comment"],$_POST["selStatus"]);
		} else if($_POST["Submit"]=="Edit") {
			editFAQ($_POST["f"]);
		} else if($_POST["Submit"]=="Add FAQ") {
			addFAQ($_POST["name"],$_POST["comment"]);
		} else if($_POST["Submit"]=="Assign To Me") {
			assignCourseToMe($_POST["cid"]);
		} else {
			if($_GET["s"]==md5("logout".session_id())) { doLogout(); }
			else if($_GET["s"]==md5("viewUnassigned".session_id())) { courseListings_unassigned(); }
			else if($_GET["s"]==md5("edituser".session_id())) { showUsersToEdit(); }
			else if($_GET["s"]==md5("editfaq".session_id())) { showFAQsToEdit(); }
			else if($_GET["s"]==md5("addcourse".session_id())) { addCourse(); }
			else {
				//default drop off point
				if($_SESSION[SESSIONPREFIX]['gid']=="1") { courseListings_CurrentAll(); }
				if($_SESSION[SESSIONPREFIX]['gid']=="2") { courseListings_myCourses(); }
				if($_SESSION[SESSIONPREFIX]['gid']=="3") { courseListings_owner(); }
			}
		}
	} else {
		if($_POST["submit"]=="Login") { doLogin($_POST["uname"],$_POST["passwd"]); }
		else if($_GET["s"]==md5("showAllFAQs")) { showAllFAQs(); }
		else if($_GET["s"]==md5("showFAQ")) { showFAQ(base64_decode($_GET["f"])); }
		else { frontPage_NotLoggedIn(); }
	}
	mainFooter();
?>