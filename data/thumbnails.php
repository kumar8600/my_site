<?php
try {
	$db = new SQLite3('./article.sqlite3');
} catch(Exception $e) {
	echo 'DBとの接続に失敗';
	die($e -> getTraceAsString());
}
$offset = $db -> escapeString($_GET['offset']);
$limit = $db -> escapeString($_GET['limit']);
if (empty($offset))
	$offset = 0;
if(empty($limit))
	$limit = 6;

$sql = "select id, timestamp, title, headimage, tag from article, fts_tag where article.rowid = fts_tag.rowid order by id desc limit $limit offset $offset;";
$result = $db -> query($sql);
$i = 0;
while ($row = $result -> fetchArray()) {
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('
<div class="span6 thu t'.$i.'" id="' . $row[id] . '">
<a href="?p=' . $row[id] . '" class="ajax">
' . $row['timestamp'] . '
<div class="thumbnail">
<img src="./data/' . $headimage_resized . ' " >
<h3> ' . $row['title'] . ' </h3>
' . $row[tag] . '
</div> </a>
</div>
');
	$i++;
}
if ($i == $limit) {
	//echo '<button class="btn thu-more">更に読み込む</button>';
	echo '<div class="thumbs-buf" style="display: none"></div>';
} else {
	echo '<div class="span6 thu" id="thu-end">はぁ〜〜〜〜<div class="thumbnail"><h3>🍣🍣🍣ページ下端🍣🍣🍣</h3></div></div>';
}



$db -> close();
?>