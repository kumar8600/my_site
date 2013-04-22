<div class="search-container hide">
	<?php
	require_once dirname(__FILE__) . '/connect-db.php';
	require_once dirname(__FILE__) . '/show-article-meta.php';
	$_GET = array_map("strip_tags", $_GET);
	if (!isset($_GET['date'])) {
		die("日付を指定してください。");
	} else {
		$date = $_GET['date'];
	}
	if (isset($_GET['offset'])) {
		$offset = $_GET['offset'];
	} else {
		$offset = 0;
	}
	if (isset($_GET['limit'])) {
		$limit = $_GET['limit'];
	} else {
		$limit = 5;
	}

	$db = connectDB();
	// 該当する件数を調べる。
	$sql = "SELECT COUNT(*) FROM article WHERE date(timestamp) = :ts";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":ts", $date);
	$result = $stmt -> execute();
	$row = $result -> fetchArray();
	$match_count = $row[0];

	$start = $offset + 1;
	$end = $offset + $limit;
	if ($end > $match_count) {
		$end = $match_count;
	}
	list($year, $month, $day) = sscanf($date, "%d-%d-%d");
	$date_ja = $year . "年" . $month . "月" . $day . "日";
	
	echo('<legend class="p-title">「'.$date_ja.'」に投稿された記事 | 全' . $match_count . '件中' . $start . '〜' . $end . '件目</legend>');
	if (0 < $offset) {
		$back_offset = $offset - $limit;
		$back_limit = $limit;
		if ($back_offset < 0) {
			$back_limit += $back_offset;
			$back_offset = 0;
		}
		echo '<a class="ajaxtags" href="?date=' . $date . '&offset=' . $back_offset . '&limit=' . $limit . '"><div class="search-func color-blue">前の' . $back_limit . '件</div></a>';
	}

	$sql = "SELECT id, timestamp, title, headimage, tag, author FROM article WHERE date(timestamp) = :ts ORDER BY article.rowid DESC LIMIT :limit OFFSET :offset";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":ts", $date);
	$stmt -> bindValue(":limit", $limit, SQLITE3_INTEGER);
	$stmt -> bindValue(":offset", $offset, SQLITE3_INTEGER);
	$result = $stmt -> execute();
	while ($row = $result -> fetchArray()) {
		$author = getAuthorById($row['author']);
		showArticleMeta($row, $author);
	}
	$db -> close();

	if ($end < $match_count) {
		$next_offset = $offset + $limit;
		echo '<a class="ajaxtags" href="?date=' . $date . '&offset=' . $next_offset . '&limit=' . $limit . '"><div class="search-func color-red">次の' . $limit . '件</div></a>';
	}
?>
</div>