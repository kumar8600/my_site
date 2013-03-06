<?php
$db = new SQLite3('../article.sqlite3');

$input_id = $db -> escapeString($_POST['id']);
// SQLiteに対する処理
$sql = "select headimage, title, body, tag from article, fts_tag where article.rowid = $input_id and fts_tag.docid = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗: ' . $sqlerror);
}
$row = $result -> fetchArray();
$row = array_map("stripslashes", $row);

echo json_encode($row);
?>