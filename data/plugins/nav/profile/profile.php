<?php
	require_once dirname(__FILE__) . '/../../../connect-db.php';
	function getAuthor($id) {
		$db = connectDB();
		$stmt = $db -> prepare("SELECT author FROM article WHERE id = :id OR title = :id");
		$stmt -> bindParam(":id", $id);
		$result = $stmt -> execute();
		$row = $result -> fetchArray();
		return $row['author'];
	}

	function getProfile($sysid) {
		$db = connectAuthDB();
		$stmt = $db -> prepare("SELECT name, userid, website, introduction FROM user WHERE sysid = :sysid");
		$stmt -> bindParam(":sysid", $sysid);
		$result = $stmt -> execute();
		$row = $result -> fetchArray();
		return $row;
	}
	
	function getProfileByUserId($userid) {
		$db = connectAuthDB();
		$stmt = $db -> prepare("SELECT name, userid, website, introduction FROM user WHERE userid = :userid");
		$stmt -> bindParam(":userid", $userid);
		$result = $stmt -> execute();
		$row = $result -> fetchArray();
		return $row;
	}
	
	if(!isset($_GET['p']) && !isset($_GET['author'])) {
		die();
	}

	if(isset($_GET['p'])) {
		$ar = $_GET['p'];
	} else {
		$ar = 'About';
	}
	if(isset($_GET['author'])) {
		$author = $_GET['author'];
		$row = getProfileByUserId($author);
	} else {
		$author = getAuthor($ar);
		$row = getProfile($author);
	}
		

	echo '<h4>' . $row['name'] . '</h4>';
	echo '<p><a href="' . $row['website'] . '">' . $row['website'] . '</a></p>';
	echo '<p>' . $row['introduction'] . '</p>';
?>