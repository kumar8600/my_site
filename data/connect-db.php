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
		die($input_rowid . 'DBクエリの実行に失敗: ' . $sqlerror);
	}
	return $result;
}

function fetchArrayDB($result) {
	$row = $result -> fetchArray(SQLITE3_ASSOC);
	if (!$row) {
		throw new Exception("クエリのフェッチ(配列への変換)に失敗");
	}
	$row = array_map("stripslashes", $row);
	return $row;
}

//注意: 取り出す要素が一列の場合のみ利用可能
function queryFetchArrayDB($db, $sql) {
	$result = queryDB($db, $sql);
	$row = fetchArrayDB($result);
	return $row;
}

function isExistDB($db, $sql) {
	$row = $db -> querySingle($sql);
	if (!$row) {
		return false;
	}
	return true;
}

function ifUnSetDie($val) {
	if ($val == "") {
		die("セットされていない値がある");
	}
}

function myCrypt($word) {
	return crypt($word, $GLOBALS['salt']);
}
?>