<?php
$db = new SQLite3('../article.sqlite3');

$input_id = $db->escapeString($_POST['id']);
// SQLiteに対する処理
$sql =  "DELETE FROM article WHERE id = $input_id;";
$sql2 = "DELETE FROM fts_tag WHERE fts_tag.docid = $input_id;";
$result = $db->query($sql);
if (!$result) {
	die('削除に失敗: ' . $sqlerror);
}
$result = $db->query($sql2);
if (!$result) {
	die('タグ削除に失敗: ' . $sqlerror);
}
echo('記事の削除に成功。');

$db->close();
?>