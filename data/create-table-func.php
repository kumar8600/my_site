<?php
require_once dirname(__FILE__) . '/connect-db.php';

function createTableAbs($db, $sql) {
	// テーブル作成を抽象化した関数。
	$result = $db -> query($sql);
	$db -> close();

	if (!$result) {
		return false;
	}
	return true;
}

function createTableArticle() {
	// 記事テーブルを作る
	$db = connectDB();
	if(isTableExists($db, "article")) {
		return true;
	}
	$sql = "CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp DEFAULT(datetime('now', 'localtime')), title, body, thumbbody, headimage, tag, author);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableAuxTag() {
	// タグ補助用テーブルを作る
	$db = connectDB();
	if(isTableExists($db, "aux_tag")) {
		return true;
	}
	$sql = "CREATE TABLE aux_tag (name TEXT, frequency INTEGER DEFAULT 1);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableAuth() {
	// ユーザー情報テーブルを作る
	$db = connectAuthDB();
	if(isTableExists($db, "user")) {
		return true;
	}
	$sql = "CREATE TABLE user (sysid INTEGER PRIMARY KEY AUTOINCREMENT, userid TEXT, password TEXT, name TEXT, email TEXT, website TEXT);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableSettings() {
	// サイト設定用テーブルを作る
	$db = connectSettingsDB();
	if(isTableExists($db, "site")) {
		return true;
	}
	$sql = "CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, description TEXT);";
	
	$ret = createTableAbs($db, $sql);
	return $ret;
}
?>