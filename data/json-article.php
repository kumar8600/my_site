<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$input_id = $db -> escapeString($_POST['id']);
// SQLiteに対する処理
$sql = "SELECT headimage, title, body, tag FROM article, fts_tag WHERE article.rowid = $input_id AND fts_tag.docid = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗: ' . $sqlerror);
}
$row = $result -> fetchArray();
$row = array_map("stripslashes", $row);

echo json_encode($row);
?>