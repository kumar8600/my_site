<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$input_tag = $db -> escapeString($_GET['tag']);
echo('<legend class="p-title">「'.$_GET['tag'].'」タグを含む記事</legend>');
echo('<div><button class="btn" id="closeTagSearch">&times;</button></div>');
$sql = "SELECT id, timestamp, title, headimage, tag FROM article WHERE tag LIKE '%". $input_tag ."%' ORDER BY article.rowid DESC;";
$result = $db -> query($sql);
while ($row = $result -> fetchArray()) {
	$row = array_map("stripslashes", $row);
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('
			<div class="span2" id="' . $row['id'] . '">
			<a href="?p=' . $row['id'] . '" class="ajax">
			' . $row['timestamp'] . '
			<div class="thumbnail tag-search">
			<img src="./data/' . $headimage_resized . ' " alt="">
			<h3> ' . $row['title'] . ' </h3>
			' . $row['tag'] . '
			</div> </a>
			</div>
			');
}
$db -> close();
?>