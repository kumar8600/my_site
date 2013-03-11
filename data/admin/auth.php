<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function authorize($userid, $password) {
	ifUnSetDie($userid);
	ifUnSetDie($password);
	
	$db = connectAuthDB();	
	
	$sql = "SELECT password FROM user WHERE userid = '" . $userid . "';";
	$row = queryFetchArray($db, $sql);

	if ($row['password'] === $password) {
		return true;
	}
	return false;
}
?>