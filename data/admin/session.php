<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function sessionLogin() {
	session_start();

	if ($_SESSION['userid'] == "") {
		session_destroy();
		die('SEX');
	}
	session_regenerate_id(TRUE);
}

function sessionLogout() {
	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	
	session_destroy();
}
?>