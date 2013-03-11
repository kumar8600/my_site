<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function authorize($userid, $password) {
	ifUnSetDie($userid);
	ifUnSetDie($password);
	
	$db = connectAuthDB();	
	
	$sql = "SELECT password FROM user WHERE userid = '" . $userid . "';";
	try {
		$row = queryFetchArrayDB($db, $sql);
	} catch (Exception $ex) {
		//echo $ex;
		throw new Exception("IDに間違いがあります。");
	}
	

	if ($row['password'] === $password) {
		return true;
	}
	throw new Exception("パスワードに間違いがあります。");
	return false;
}
?>