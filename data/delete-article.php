<?php
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/admin/session.php';
$db = connectDB();

ifUnSetDie($_POST['id']);
$input_id = $db -> escapeString($_POST['id']);
$input_author = getSessionUser();

$sql = "SELECT author FROM article WHERE id = " . $input_id . ";";
$row = queryFetchArrayDB($db, $sql);
if ($row['author'] != $input_author) {
	die("この記事を削除する権限を持っていません。");
}

// SQLiteに対する処理
$sql = "DELETE FROM article WHERE id = $input_id;";
$sql2 = "DELETE FROM fts_tag WHERE fts_tag.docid = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('削除に失敗: ' . $sqlerror);
}
$result = $db -> query($sql2);
if (!$result) {
	die('タグ削除に失敗: ' . $sqlerror);
}
echo('記事の削除に成功。');

$db -> close();
?>