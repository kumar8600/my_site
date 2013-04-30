<?php
require_once dirname(__FILE__) . '/connect-db.php';

function initPrivateDB() {
	if(is_dir($GLOBALS['db_private_path'])) {
		return true;
	}
	if(mkdir($GLOBALS['db_private_path'], 0755)) {
		return true;
	}
	return false;
}

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
	$sql = "CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp DEFAULT(datetime('now')), title TEXT, body TEXT, preface TEXT, headimage TEXT, tag TEXT, author INTEGER);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableLog() {
	// アクセスログテーブルを作る
	$db = connectLogsDB();
	if(isTableExists($db, "log")) {
		return true;
	}
	$sql = "CREATE TABLE log (id INTEGER PRIMARY KEY AUTOINCREMENT, articleid INTEGER, timestamp DEFAULT(datetime('now')));";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableRankLog() {
	// アクセスランクテーブルを作る
	$db = connectLogsDB();
	if(isTableExists($db, "rank")) {
		return true;
	}
	$sql = "CREATE TABLE rank (id INTEGER PRIMARY KEY AUTOINCREMENT, articleid INTEGER, ranking INTEGER, freq INTEGER, auxid INTEGER);";
	
	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableAuxRank() {
	// アクセスランク補助情報テーブルを作る
	$db = connectLogsDB();
	if(isTableExists($db, "aux_rank")) {
		return true;
	}
	// periodは期間。0: 累計, 1: デイリー, 2: ウィークリー（週に一度集計）, 3: マンスリー（月に一度集計）, 7: 7日分（毎日集計）, 31: 31日分（毎日集計）
	$sql = "CREATE TABLE aux_rank (id INTEGER PRIMARY KEY AUTOINCREMENT, period INTEGER, timestamp DEFAULT(datetime('now')));";
	
	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableMapTag() {
	// タグ用テーブルを作る
	$db = connectDB();
	if(isTableExists($db, "map_tag")) {
		return true;
	}
	$sql = "CREATE TABLE map_tag (tagid INTEGER, articleid INTEGER);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableAuxTag() {
	// タグ補助用テーブルを作る
	$db = connectDB();
	if(isTableExists($db, "aux_tag")) {
		return true;
	}
	$sql = "CREATE TABLE aux_tag (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, frequency INTEGER DEFAULT 1);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableAuth() {
	// ユーザー情報テーブルを作る
	$db = connectAuthDB();
	if(isTableExists($db, "user")) {
		return true;
	}
	$sql = "CREATE TABLE user (sysid INTEGER PRIMARY KEY AUTOINCREMENT, userid TEXT, password TEXT, name TEXT, email TEXT, website TEXT, introduction TEXT);";

	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableSettings() {
	// サイト設定用テーブルを作る
	$db = connectSettingsDB();
	if(isTableExists($db, "site")) {
		return true;
	}
	$sql = "CREATE TABLE site (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, description TEXT, allowregist INTEGER DEFAULT 0);";
	
	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableNav() {
	// ナビゲーションカラム用テーブルを作る
	$db = connectSettingsDB();
	if(isTableExists($db, "nav")) {
		return true;
	}
	$sql = "CREATE TABLE nav (id INTEGER PRIMARY KEY, folder TEXT, page TEXT, configid INTEGER);";
	
	$ret = createTableAbs($db, $sql);
	return $ret;
}

function createTableComment() {
	// コメント用テーブルを作る
	$db = connectCommentsDB();
	if(isTableExists($db, "comment")) {
		return true;
	}
	$sql = "CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp DEFAULT(datetime('now')), subid INTEGER, articleid INTEGER, name TEXT, email TEXT, ip TEXT, body TEXT);";
	
	$ret = createTableAbs($db, $sql);
	return $ret;
}
?>