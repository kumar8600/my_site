<?php
require_once dirname(__FILE__) . '/connect-db.php';

function addAuxTag($name) {
	if(!isset($name)) {
		return false;
	}
	
	$db = connectDB();
	
	$sql = "SELECT frequency FROM aux_tag WHERE name = '$name';";
	$result = queryDB($db, $sql);
	$row = $result -> fetchArray(SQLITE3_ASSOC);
	
	if($row) {
		$freq = $row['frequency'] + 1;
		$sql = "UPDATE aux_tag SET frequency = $freq WHERE name = '$name';";
	} else {
		$sql = "INSERT INTO aux_tag VALUES('$name', 1)";
	}
	
	queryDB($db, $sql);
	
	$db -> close();
	return true;
}
?>