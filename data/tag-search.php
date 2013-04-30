<div class="search-container hide">
	<?php
	require_once dirname(__FILE__) . '/connect-db.php';
	require_once dirname(__FILE__) . '/show-article-meta.php';
	$_GET = array_map("strip_tags", $_GET);
	if (!isset($_GET['tag'])) {
		die("タグを指定してください。");
	} else {
		$input_tag = $_GET['tag'];
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
	//$sql = "SELECT COUNT(*) FROM article WHERE tag LIKE :tag";
	$sql = "SELECT COUNT(*) FROM map_tag WHERE tagid = (SELECT id FROM aux_tag WHERE name = :name)";
	$stmt = $db -> prepare($sql);
	//$stmt -> bindValue(":tag", "%" . $input_tag . "%");
	$stmt -> bindValue(":name", $input_tag);
	$result = $stmt -> execute();
	$row = $result -> fetchArray();
	$match_count = $row[0];

	$start = $offset + 1;
	$end = $offset + $limit;
	if ($end > $match_count) {
		$end = $match_count;
	}

	echo('<legend class="p-title">「' . $input_tag . '」タグを含む記事 | 全' . $match_count . '件中' . $start . '〜' . $end . '件目</legend>');
	if (0 < $offset) {
		$back_offset = $offset - $limit;
		$back_limit = $limit;
		if ($back_offset < 0) {
			$back_limit += $back_offset;
			$back_offset = 0;
		}
		echo '<a class="ajaxtags" href="?tag=' . $input_tag . '&offset=' . $back_offset . '&limit=' . $limit . '"><div class="search-func color-blue">前の' . $back_limit . '件</div></a>';
	}

	//$sql = "SELECT id, timestamp, title, headimage, tag, author FROM article WHERE tag LIKE :tag ORDER BY article.rowid DESC LIMIT :limit OFFSET :offset";
	$sql = "SELECT id, datetime(timestamp, 'localtime') as timestamp, title, headimage, tag, author FROM article WHERE id = (SELECT articleid FROM map_tag WHERE tagid = (SELECT id FROM aux_tag WHERE name = :tag)) ORDER BY article.rowid DESC LIMIT :limit OFFSET :offset";
	$stmt = $db -> prepare($sql);
	//$stmt -> bindValue(":tag", "%" . $input_tag . "%");
	$stmt -> bindValue(":tag", $input_tag);
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
		echo '<a class="ajaxtags" href="?tag=' . $input_tag . '&offset=' . $next_offset . '&limit=' . $limit . '"><div class="search-func color-red">次の' . $limit . '件</div></a>';
	}
?>
</div>