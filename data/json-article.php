<?php
$input_id = sqlite_escape_string($_POST['id']);

try {
	$db = new SQLite3('./article.sqlite3');
} catch(Exception $e) {
	echo 'DBとの接続に失敗';
	die($e -> getTraceAsString());
}
// SQLiteに対する処理
$sql = "select headimage, title, body, tag from article, fts_tag where article.rowid = $input_id and fts_tag.rowid = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗: ' . $sqlerror);
}
$row = $result -> fetchArray();

echo json_encode($row);
?>