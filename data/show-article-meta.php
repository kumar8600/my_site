<?php
require_once dirname(__FILE__) . '/connect-db.php';

function showArticleMeta($row, $author) {
	//rowとauthorより、記事情報をhtmlで行表示します
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('	<div class="search" id="s' . $row['id'] . '">');
	echo('	<a href="?p=' . $row['id'] . '" class="ajax">
			<img src="./data/' . $headimage_resized . ' " alt=""></a>');
	echo('	<div class="search-meta">
			<span class="label label-info">' . $row['timestamp'] . '</span>');
	$tags = preg_split("/\s+/", $row['tag'], -1, PREG_SPLIT_NO_EMPTY);
	echo '<span>';
	for ($i = 0; $i < count($tags); $i++) {
		echo('<a href="?tag=' . $tags[$i] . '" class="ajaxtags"><span class="badge"><i class="icon-tag icon-white"></i>' . $tags[$i] . '</span></a>');
	}
	if ($author == null) {
		echo '不明';
	} else {
		echo '<a href="?author=' . $author['userid'] . '" class="ajaxtags ar-author"><span class="badge badge-warning"><i class="icon-user icon-white"></i>' . $author['name'] . '</span></a>';
	}
	echo '</span>';
	echo('	<a href="?p=' . $row['id'] . '" class="ajax"><h2 class="search-title">' . $row['title'] . ' </h2></a>
			</div>
			</div>
			');
}
?>