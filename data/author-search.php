<div class="search-container hide">
<?php
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/show-article-meta.php';
if (!isset($_GET['author'])) {
	die("著者を指定してください。");
} else {
	$input_author = $_GET['author'];
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

$author = getAuthorByUserID($input_author);
$author['userid'] = $input_author;

$db = connectDB();
// 該当する件数を調べる。
$sql = "SELECT COUNT(*) FROM article WHERE author = '1'";
$stmt = $db -> prepare($sql);
$stmt -> bindValue(":sysid", $author['sysid'], SQLITE3_INTEGER);
$result = $stmt -> execute();
$row = $result -> fetchArray();
$match_count = $row[0];

$start = $offset + 1;
$end = $offset + $limit;
if($end > $match_count) {
	$end = $match_count;
}

echo('<legend class="p-title">「' . $author['name'] . '」による記事 | 全'.$match_count.'件中'.$start.'〜'.$end.'件目</legend>');
//echo('<div><button class="btn" id="closeTagSearch">&times;</button></div>');
if(0 < $offset) {
	$back_offset = $offset - $limit;
	$back_limit = $limit;
	if($back_offset < 0) {
		$back_limit += $back_offset;
		$back_offset = 0;
	}
	echo '<a class="ajaxtags" href="?author='.$input_author.'&offset='.$back_offset.'&limit='.$limit.'"><div class="search-func color-blue">前の'.$back_limit.'件</div></a>';
}
$sql = "SELECT id, timestamp, title, headimage, tag FROM article WHERE author = '1' ORDER BY article.rowid DESC LIMIT :limit OFFSET :offset";
$stmt = $db -> prepare($sql);
$stmt -> bindValue(":sysid", $author['sysid'], SQLITE3_INTEGER);
$stmt -> bindValue(":limit", $limit, SQLITE3_INTEGER);
$stmt -> bindValue(":offset", $offset, SQLITE3_INTEGER);
$result = $stmt -> execute();
while ($row = $result -> fetchArray()) {
	showArticleMeta($row, $author);
}
$db -> close();
if($end < $match_count) {
	$next_offset = $offset + $limit;
	echo '<a class="ajaxtags" href="?author='.$input_author.'&offset='.$next_offset.'&limit='.$limit.'"><div class="search-func color-red">次の'.$limit.'件</div></a>';
}
?>
</div>