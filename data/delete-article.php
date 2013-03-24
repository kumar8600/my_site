<?php
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/admin/session.php';
require_once dirname(__FILE__) . '/aux-tag.php';
$db = connectDB();

ifUnSetDie($_POST['id']);
$input_id = $db -> escapeString($_POST['id']);
$input_author = getSessionUser();

$sql = "SELECT author, tag FROM article WHERE id = " . $input_id . ";";
$row = queryFetchArrayDB($db, $sql);
if ($row['author'] != $input_author) {
	die("この記事を削除する権限を持っていません。");
}

// SQLiteに対する処理
$sql = "DELETE FROM article WHERE id = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('削除に失敗: ' . $sqlerror);
}

// タグ補助テーブルに対する処理
$row['tag'] = preg_split("/\s+/", $row['tag'], -1, PREG_SPLIT_NO_EMPTY);
updateAuxTags(null, $row['tag']);
echo('記事の削除に成功。');

$db -> close();
?>