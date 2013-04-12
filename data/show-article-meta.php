<?php
require_once dirname(__FILE__) . '/connect-db.php';

function showTimeStamp($timestamp, $full_detail = false) {
	// 2012-04-05 07:21:45みたいな感じのください
	list($year, $month, $day, $hour, $min, $sec) = sscanf($timestamp, "%d-%d-%d %d:%d:%d");
	//echo '<span class="label label-info">' . $year . $month . $day . '</span>';
	$time = $hour . ':' . $min . ':' . $sec;
	echo '<div class="date-container">
			<div class="month">' . $month . '月</div>
			<div class="day">' . $day . '</div>';
	if ($full_detail)
		echo '<div class="time">' . $time . '</div>';
	echo '<div class="year">' . $year . '</div>
		</div>';
}

function showArticleMeta($row, $author) {
	//rowとauthorより、記事情報をhtmlで行表示します
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo('	<div class="search" id="s' . $row['id'] . '">');
	showTimeStamp($row['timestamp']);
	echo('	<a href="?p=' . $row['id'] . '" class="ajax">
			<img src="./data/' . $headimage_resized . ' " alt=""></a>');
	echo('	<div class="search-meta">');
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
	echo('	<a href="?p=' . $row['id'] . '" class="ajax"><h4 class="search-title">' . $row['title'] . ' </h4></a>
			</div>
			<div style="clear: both;"></div>
			</div>
			');
}
?>