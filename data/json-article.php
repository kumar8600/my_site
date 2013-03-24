<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$input_id = $db -> escapeString($_POST['id']);
// SQLiteに対する処理
$sql = "SELECT headimage, title, body, author, tag FROM article WHERE article.rowid = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗: ' . $sqlerror);
}
$row = $result -> fetchArray();

$row = array_map("stripslashes", $row);
$row['title'] = htmlspecialchars_decode($row['title']);
$row['tag'] = htmlspecialchars_decode($row['tag']);

echo json_encode($row);
?>