<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function setSession($key, $val) {
	session_start();
	$_SESSION[$key] = $val;
	session_write_close();
}

function getSessionUser() {
	session_start();

	if (!isset($_SESSION['userid'])) {
		session_destroy();
		return false;
	}
	session_regenerate_id(TRUE);
	session_write_close();
	return $_SESSION['userid'];
}

function getSessionSysId() {
	session_start();

	if (!isset($_SESSION['sysid'])) {
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
	// $strがセッションユーザーのSysIdであるか、rootユーザーとしてログイン中であれば$strにかかわらずtrueを返す関数
	if(isset($GLOBALS['ses_sysid'])) {
		$ses_sysid = $GLOBALS['ses_sysid'];
	} else {
		$ses_sysid = getSessionSysId();
	}
	if(isset($GLOBALS['sesuserid'])) {
		$sesuserid = $GLOBALS['sesuserid'];
	} else {
		$sesuserid = getSessionUser();
	}
	if($ses_sysid == $str || $sesuserid == "root") {
		return true;
	} else {
		return false;
	}
}

function isRootUser() {
	// rootユーザーとしてログイン中であればtrueを返す関数
	if(isset($GLOBALS['sesuserid'])) {
		$sesuserid = $GLOBALS['sesuserid'];
	} else {
		$sesuserid = getSessionUser();
	}
	if($sesuserid == "root") {
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