<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$input_author = $db -> escapeString($_GET['author']);
echo('<div><button class="btn" id="closeTagSearch">「' . $input_author . '」が書いた記事の検索をやめる</button></div>');
$sql = "SELECT id, timestamp, title, headimage, tag FROM article, fts_tag WHERE article.rowid = fts_tag.rowid AND article.rowid IN(SELECT article.rowid FROM article WHERE author = '$input_author') ORDER BY article.rowid DESC;";
$result = $db -> query($sql);
while ($row = $result -> fetchArray()) {
	$row = array_map("stripslashes", $row);
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('
			<div class="span2" id="' . $row[id] . '">
			<a href="?p=' . $row[id] . '" class="ajax">
			' . $row['timestamp'] . '
			<div class="thumbnail tag-search">
			<img src="./data/' . $headimage_resized . ' " alt="">
			<h3> ' . $row['title'] . ' </h3>
			' . $row[tag] . '
			</div> </a>
			</div>
			');
}
$db -> close();
?>