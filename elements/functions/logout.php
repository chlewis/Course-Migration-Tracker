<?PHP
	function doLogout() {
		echo '<p>You are being logged out of the EduCat Migration system. You will be redirected in a few seconds.</p>';
		echo '<meta http-equiv="refresh" content="2;url=https://'.SERVER.'">';
		$_SESSION[SESSIONPREFIX]['valid'] = false;
		$_SESSION[SESSIONPREFIX]['private_key'] = NULL;
		$_SESSION[SESSIONPREFIX]['uid'] = NULL;
		$_SESSION = array();
		session_destroy();
		@session_regenerate_id();
	}

?>