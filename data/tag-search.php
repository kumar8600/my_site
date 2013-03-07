<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$input_tag = $db -> escapeString($_GET['tag']);
echo('<div><button class="btn" id="closeTagSearch">「' . $input_tag . '」タグの検索をやめる</button></div>');
$sql = "select id, timestamp, title, headimage, tag from article, fts_tag where article.rowid = fts_tag.rowid and article.rowid in(select fts_tag.rowid from fts_tag where tag match '$input_tag') order by article.rowid desc;";
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