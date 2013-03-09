<?php
require_once dirname(__FILE__) . '/../config.php';

function connectDB() {
	$db = new SQLite3($GLOBALS['db_path']);
	return $db;
}

function connectAuthDB() {
	$db = new SQLite3($GLOBALS['auth_db_path']);
	return $db;
}

function queryDB($db, $sql) {
	$result = $db -> query($sql);

	if (!$result) {
		die($input_rowid . '記事のインサートクエリーに失敗: ' . $sqlerror);
	}
	return $result;
}

function fetchArrayDB($result) {
	$row = $result -> fetchArray();
	if (count($row) == 0) {
		die("データの配列の取得に失敗。");
	}
	return $row;
}

function queryFetchArrayDB($db, $sql) {
	$result = queryDB($db);
	$row = fetchArrayDB($result);
	return $row;
}

function ifUnSetDie($val) {
	if ($val == "") {
		die("セットされていない値がある");
	}
}
?>