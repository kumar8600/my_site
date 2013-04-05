<?php
require_once dirname(__FILE__) . '/data/connect-db.php';
$db = connectDB();

// SQLiteに対する処理
$sql = "SELECT * FROM article WHERE id = :id OR title = :id";
$stmt = $db -> prepare($sql);
$stmt -> bindValue(":id", $_GET['p']);
$result = $stmt -> execute();
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
echo '<div class="ar-main">';
echo '<div class="ar-head">';
echo '<h1 class="p-title ar-title"><a class="ajax" href=?p='. $row['id'] .'>'. $row['title']. '</a></h1>';
echo '<div style="ar-meta"><span class="label label-info">'.$row['timestamp'].'</span>';
$tags = preg_split("/\s+/", $row['tag'], -1, PREG_SPLIT_NO_EMPTY);
echo '<span>';
for ($i = 0; $i < count($tags); $i++) {
	echo('<a href="?tag=' . $tags[$i] . '" class="ajaxtags"><span class="badge"><i class="icon-tag icon-white"></i>' . $tags[$i] . '</span></a>');
}
echo '</span>';
echo '<span>';
if($author == null) {
	echo '不明';
} else {
	echo '<a href="?author='. $author['userid'] .'" class="ajaxtags ar-author"><span class="badge badge-warning"><i class="icon-user icon-white"></i>'. $author['name'] .'</span></a>';
}
echo '</span>';
echo '<div class="admin-article"></div></div></div>';
echo '<div class="ar-headimage"><img src="./data/' . $headimage_resized . '" /></div>';
echo '<div class="ar-body">' . $row['body'] . '</div>';
echo '</div>';
echo '<div class="ar-footer">';
echo '</div>';
require dirname(__FILE__) . '/data/article-footer.php';
?>