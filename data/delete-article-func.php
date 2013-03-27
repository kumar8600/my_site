<?php
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/aux-tag.php';

function deleteArticle($id, $author) {
	$db = connectDB();

	$sql = "SELECT author, tag FROM article WHERE id = " . $id . ";";
	$row = queryFetchArrayDB($db, $sql);
	if ($row['author'] != $author) {
		die("この記事を削除する権限を持っていません。");
	}

	// SQLiteに対する処理
	$sql = "DELETE FROM article WHERE id = $id;";
	$result = $db -> query($sql);
	if (!$result) {
		die('削除に失敗: ' . $sqlerror);
	}

	// タグ補助テーブルに対する処理
	$row['tag'] = preg_split("/\s+/", $row['tag'], -1, PREG_SPLIT_NO_EMPTY);
	updateAuxTags(null, $row['tag']);

	$db -> close();
}
?>