<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function listComments($ar_id, $start, $count) {
	$db = connectCommentsDB();
	
	$sql = "SELECT *, datetime(timestamp, 'localtime') as timestamp FROM comment WHERE articleid = :arid ORDER BY id LIMIT :lim OFFSET :off";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":arid", $ar_id, SQLITE3_INTEGER);
	$stmt -> bindValue(":lim", $count, SQLITE3_INTEGER);
	$stmt -> bindValue(":off", $start, SQLITE3_INTEGER);
	
	$result = $stmt -> execute();
	echo '<ul class="comment">';
	while ($row = $result -> fetchArray()) {
		echo '<li class="comment">';
		echo '<div class="meta">';
		echo '<p class="meta-subid">'. $row['subid'] . '</p>';
		echo '<p class="meta-name">'. $row['name'];
		echo '<span class="meta-time">'. $row['timestamp'] . '</span></p></div>';
		echo '<div class="com-body"><p>'. $row['body'] . '</p></div>';
		echo '</li>';
	}
	echo '</ul>';
}

function getCommentCount($ar_id) {
	$db = connectCommentsDB();
	
	$sql = "SELECT COUNT(subid) FROM comment WHERE articleid = :arid";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":arid", $ar_id, SQLITE3_INTEGER);
	
	$result = $stmt -> execute();
	
	$row = $result -> fetchArray();
	
	return $row[0];
}

function sendComments($ar_id, $name, $email, $ip, $body) {
	$subid = getCommentCount($ar_id) + 1;
	if($subid > 1000) {
		die("一つの記事につきコメントは1000個までです。");
	}
	
	$db = connectCommentsDB();
	
	$sql = "INSERT INTO comment(subid, articleid, name, email, ip, body) VALUES(:subid, :arid, :name, :email, :ip, :body)";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":subid", $subid, SQLITE3_INTEGER);
	$stmt -> bindValue(":arid", $ar_id, SQLITE3_INTEGER);
	$stmt -> bindValue(":name", $name, SQLITE3_TEXT);
	$stmt -> bindValue(":email", $email, SQLITE3_TEXT);
	$stmt -> bindValue(":ip", $ip, SQLITE3_TEXT);
	$stmt -> bindValue(":body", $body, SQLITE3_TEXT);
	
	$result = $stmt -> execute();
	
	if($result == false) {
		return false;
	}
	return true;
}
?>