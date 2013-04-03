<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$offset = $db -> escapeString($_GET['offset']);
$limit = $db -> escapeString($_GET['limit']);
//	パラメータがなくてもデフォルト値を指定する
if (empty($offset))
	$offset = 0;
if (empty($limit))
	$limit = 6;

$sql = "SELECT * FROM article ORDER BY id desc LIMIT $limit OFFSET $offset;";
$result = $db -> query($sql);

$i = $offset;
$c = 0;
while ($row = $result -> fetchArray()) {
	$row = array_map("stripslashes", $row);
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('
			<div class="thu'. $i .'" id="' . $row['id'] . '">
			<a href="?p=' . $row['id'] . '" class="ajax">
			' . $row['timestamp'] . '
			<div class="thumbnail">
			<img src="./data/' . $headimage_resized . ' " >
			<h3 class="title' . $row['id'] . '"> ' . $row['title'] . ' </h3>
			<div class="tag' . $row['id'] . '">' . $row['tag'] . '</div>
			</div> </a>
			</div>
			');
	$i++;
	$c++;
}
$db -> close();

if ($c == $limit) {
	echo '<div class="thumbs-buf" style="display: none"></div>';
} else {
	echo '<div class="thumbs-buf end"></div>';
}
?>