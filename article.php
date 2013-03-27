<?php
require_once dirname(__FILE__) . '/data/connect-db.php';
$db = connectDB();

$input_id = $db -> escapeString($_GET['p']);
// SQLiteに対する処理
$sql = "SELECT * FROM article WHERE id = '$input_id' OR title = '$input_id';";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗:' . $sqlerror);
}
$row = $result -> fetchArray();
if ($row['title'] == null) {
	die("指定された記事がありません🍣");
}
$db -> close();
$row = array_map("stripslashes", $row);

$db2 = connectAuthDB();
$sql = "SELECT userid, name FROM user WHERE sysid = '". $row['author']. "';";
try {
		$author = queryFetchArrayDB($db2, $sql);
	} catch(Exception $ex) {
		$author = null;
	}
$db2 -> close();

$dotpos = strrpos($row['headimage'], '.');
$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x640' . substr($row['headimage'], $dotpos);
echo '<div class="admin-article"></div>';
echo $row['timestamp'];
echo '<h1 id="ar-title">', $row['title'], '</h1>';
echo '<div id="ar-headimage"><img src="./data/' . $headimage_resized . '" /></div>';
echo '<div id="ar-body">' . $row['body'] . '</div>';
echo '<br />タグ: <div id="ar-tag">';
$tags = preg_split("/\s+/", $row['tag'], -1, PREG_SPLIT_NO_EMPTY);
for ($i = 0; $i < count($tags); $i++) {
	echo('<a href="?tag=' . $tags[$i] . '" class="ajaxtags">' . $tags[$i] . ' </a>');
}
echo '</div>';
if($author == null) {
	echo '著者: 不明';
} else {
	echo '著者: <a href="?author='. $author['userid'] .'" id="ar-author">'. $author['name'] .'</div>';
}


?>