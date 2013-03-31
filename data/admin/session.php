<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function setSession($key, $val) {
	session_start();
	$_SESSION[$key] = $val;
	session_write_close();
}

function getSessionUser() {
	session_start();

	if ($_SESSION['userid'] == "") {
		session_destroy();
		return false;
	}
	session_regenerate_id(TRUE);
	session_write_close();
	return $_SESSION['userid'];
}

function getSessionSysId() {
	session_start();

	if ($_SESSION['sysid'] == "") {
		session_destroy();
		return false;
	}
	session_regenerate_id(TRUE);
	session_write_close();
	return $_SESSION['sysid'];
}

function getSysIdByDB($userid) {
	$db = connectAuthDB();
	$sql = "SELECT sysid FROM user WHERE userid = '$userid'";
	$row = queryFetchArrayDB($db, $sql);
	$db -> close();

	return $row['sysid'];
}

function getUserIdByDB($sysid) {
	$db = connectAuthDB();
	$sql = "SELECT userid FROM user WHERE sysid = '$sysid'";
	$row = queryFetchArrayDB($db, $sql);
	$db -> close();

	return $row['userid'];
}

function setSessionUser($userid) {
	setSession('userid', $userid);
	setSession('sysid', getSysIdByDB($userid));
}

function isSysIdOrRoot($str) {
	// $strがセッションユーザー名であるか、セッションユーザー名が"root"であれば$strにかかわらずtrueを返す関数
	$ses_sysid = $GLOBALS['ses_sysid'];
	if ($ses_sysid == "") {
		$ses_sysid = getSessionSysId();
	}
	$sesuserid = $GLOBALS['sesuserid'];
	if ($sesuserid == "") {
		$sesuserid = getSessionUser();
	}
	if($ses_sysid == $str || $sesuserid == "root") {
		return true;
	} else {
		return false;
	}
}

function sessionLogout() {
	session_start();
	$_SESSION = array();

	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}

	session_destroy();
}
?>