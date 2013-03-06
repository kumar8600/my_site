<?php
require_once '../functions.php';
$db = sqliteOpen();

$offset = $db -> escapeString($_GET['offset']);
$limit = $db -> escapeString($_GET['limit']);
if (empty($offset))
	$offset = 0;
if(empty($limit))
	$limit = 6;

$sql = "select id, timestamp, title, headimage, tag from article, fts_tag where article.rowid = fts_tag.docid order by id desc limit $limit offset $offset;";
$result = $db -> query($sql);
$i = 0;
while ($row = $result -> fetchArray()) {
	$row = array_map("stripslashes", $row);
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('
			<div class="span6 thu" id="' . $row['id'] . '">
			<a href="?p=' . $row['id'] . '" class="ajax">
			' . $row['timestamp'] . '
			<div class="thumbnail">
			<img src="./data/' . $headimage_resized . ' " >
			<h3 class="title'. $row['id'] .'"> ' . $row['title'] . ' </h3>
			<div class="tag'. $row['id'] .'">' . $row[tag] . '</div>
			</div> </a>
			</div>
			');
	$i++;
}
if ($i == $limit) {
	//echo '<button class="btn thu-more">更に読み込む</button>';
	echo '<div class="thumbs-buf" style="display: none"></div>';
} else {
	echo '<div class="thumbs-buf end"></div>';
}



$db -> close();
?>