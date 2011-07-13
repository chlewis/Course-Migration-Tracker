<?PHP
	//define system options
	define("USELDAP",false); //use LDAP authentication
	define("USESSL",true); //use SSL for all pages

	//define static vars
	define("DIR","/path/to/dir"); //where the files reside server-side
	define("SERVER","url.to.site/"); //your server name without http://www and with a trailing /
	$ldap_server = array("ldap.address.here"); //the array of ldap servers for round-robin authentication
	define("SESSIONPREFIX","SESSIONPREFIX"); //the session variables are stored in a superarray with this name
	define("EMAILFROM","SOME NAME"); //The From Name in email
	define("EMAILADDR","someaddr@you.edu"); //the reply to and from email address
	define("MOODLEURL","https://moodle.yoursite.edu"); //the url for Moodle in the email
	
	//define session variables
	$_SESSION[SESSIONPREFIX]['dbserver'] = "";
	$_SESSION[SESSIONPREFIX]['dbuser'] = "";
	$_SESSION[SESSIONPREFIX]['dbpass'] = "";
	$_SESSION[SESSIONPREFIX]['dbase'] = "";
	$_SESSION[SESSIONPREFIX]['orauser'] = "";
	$_SESSION[SESSIONPREFIX]['orapass'] = "";

	//get classes
	require_once(DIR."/elements/classes/rss_php.php");
	require_once(DIR."/elements/classes/db.class.php");
	require_once(DIR."/elements/classes/class.phpmailer.php");
	
	//get functions
	require_once(DIR."/elements/functions/mainfuncs.php");
	require_once(DIR."/elements/functions/login.php");
	require_once(DIR."/elements/functions/logout.php");
	require_once(DIR."/elements/functions/listings.php");
	require_once(DIR."/elements/functions/disCourse.php");
	require_once(DIR."/elements/functions/faqs.php");
	require_once(DIR."/elements/functions/users.php");
?>