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
$data_article = $result -> fetchArray();
if ($data_article['title'] == null) {
	die("指定された記事がありません🍣");
}
// FTSテーブルからも情報取ってくる
$input_id = $data_article['id'];
$sql = "SELECT * FROM fts_tag WHERE fts_tag.docid = $input_id;";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗: ' . $sqlerror);
}
$data_fts = $result -> fetchArray();

$data_article = array_map("stripslashes", $data_article);
$data_fts = array_map("stripslashes", $data_fts);
$dotpos = strrpos($data_article['headimage'], '.');
$headimage_resized = substr($data_article['headimage'], 0, $dotpos) . 'x640' . substr($data_article['headimage'], $dotpos);
echo '<div class="admin-article"></div>';
echo $data_article['timestamp'];
echo '<h1 id="ar-title">', $data_article['title'], '</h1>';
echo '<div id="ar-headimage"><img src="./data/' . $headimage_resized . '" /></div>';
echo '<div id="ar-body">' . $data_article['body'] . '</div>';
echo '<br />タグ: <div id="ar-tag">';
$tags = explode(" ", $data_fts['tag']);
for ($i = 0; $i < count($tags); $i++) {
	echo('<a href="?tag=' . $tags[$i] . '" class="ajaxtags">' . $tags[$i] . ' </a>');
}
echo '</div>';

$db -> close();
?>
<!-- Modal -->
