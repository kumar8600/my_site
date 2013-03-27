<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function authorize($userid, $password) {
	ifUnSetDie($userid);
	ifUnSetDie($password);
	
	$db = connectAuthDB();	
	
	$userid = $db -> escapeString($userid);
	$password = $db -> escapeString($password);
	
	$sql = "SELECT password FROM user WHERE userid = '" . $userid . "';";
	try {
		$row = queryFetchArrayDB($db, $sql);
	} catch (Exception $ex) {
		//echo $ex;
		throw new Exception("IDに間違いがあります。");
	}
	
	if (crypt($password, $row['password']) == $row['password']) {
		return true;
	}
	throw new Exception("パスワードに間違いがあります。");
	return false;
}

function authorizeSysId($sysid, $password) {
	ifUnSetDie($sysid);
	ifUnSetDie($password);
	
	$db = connectAuthDB();	
	
	$sysid = $db -> escapeString($sysid);
	$password = $db -> escapeString($password);
	
	$sql = "SELECT password FROM user WHERE sysid = '" . $sysid . "';";
	try {
		$row = queryFetchArrayDB($db, $sql);
	} catch (Exception $ex) {
		//echo $ex;
		throw new Exception("SYSIDに間違いがあります。");
	}
	
	if (crypt($password, $row['password']) == $row['password']) {
		return true;
	}
	throw new Exception("パスワードに間違いがあります。");
	return false;
}
?>