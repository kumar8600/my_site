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

function connectSettingsDB() {
	$db = new SQLite3($GLOBALS['set_db_path']);
	return $db;
}

function connectCommentsDB() {
	$db = new SQLite3($GLOBALS['comments_db_path']);
	return $db;
}

function connectLogsDB() {
	$db = new SQLite3($GLOBALS['logs_db_path']);
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

function isTableExists($db, $name) {
	// $nameが名前のテーブルが存在するか調べる関数。
	$sql = "SELECT COUNT(*) FROM sqlite_master WHERE type = 'table' AND name = :name";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name, SQLITE3_TEXT);
	$result = $stmt -> execute();
	$row = $result -> fetchArray(SQLITE3_NUM);
	if ($row[0] == 0) {
		return false;
	}
	return true;
}

function isRootExists() {
	//rootユーザーが存在するか調べる関数。
	try {
		$db = connectAuthDB();
	} catch(Exception $ex) {
		return false;
	}
	$sql = "SELECT COUNT(*) FROM sqlite_master WHERE type = 'table' AND name = 'user';";
	$row = $db -> querySingle($sql);
	if ($row != 0) {
		$sql = "SELECT COUNT(userid) FROM user WHERE userid = 'root';";
		$row = $db -> querySingle($sql);
		if ($row == 1) {
			return true;
		}
	}
	return false;
}

function canRegister() {
	$db = connectSettingsDB();
	$sql = "SELECT allowregist FROM site WHERE id = 1;";
	$row = $db -> querySingle($sql);
	if ($row == 1) {
		return true;
	}
	return false;
}

function ifUnSetDie($val) {
	if ($val == "") {
		die("セットされていない値がある");
	}
}

function getSiteName() {
	$db = connectSettingsDB();
	$sql = "SELECT name FROM site WHERE id = 1;";
	$result = $db -> query($sql);
	$row = $result -> fetchArray();
	return $row['name'];
}

function getAuthorById($id) {
	$db = connectAuthDB();
	$sql = "SELECT userid, name FROM user WHERE sysid = :id;";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":id", $id);
	try {
		$result = $stmt -> execute();
		$author = $result -> fetchArray();
	} catch(Exception $ex) {
		$author = null;
	}
	$db -> close();
	return $author;
}

function getAuthorByUserId($userid) {
	$db = connectAuthDB();
	$sql = "SELECT sysid, name FROM user WHERE userid = :userid";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":userid", $userid);
	$result = $stmt -> execute();

	$author = $result -> fetchArray();
	$db -> close();
	return $author;
}

function myCrypt($word) {
	return crypt($word, $GLOBALS['salt']);
}
?>