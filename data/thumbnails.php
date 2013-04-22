<?php
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/thum-social-buttons.php';
require_once dirname(__FILE__) . '/show-article-meta.php';
//JSONをつかぅょ、だからechoをすべてバッファリングするょ
ob_start();
$_GET = array_map("strip_tags", $_GET);
if (isset($_GET['offset'])) {
	$offset = $_GET['offset'];
} else {
	$offset = 0;
}
if (isset($_GET['limit'])) {
	$limit = $_GET['limit'];
} else {
	$limit = 6;
}

$db = connectDB();
$sql = "SELECT * FROM article ORDER BY id desc LIMIT :limit OFFSET :offset;";
$stmt = $db -> prepare($sql);
$stmt -> bindValue(":limit", $limit);
$stmt -> bindValue(":offset", $offset);
$result = $stmt -> execute();
$name = getSiteName();

$i = $offset;
$c = 0;
while ($row = $result -> fetchArray()) {
	$db2 = connectAuthDB();
	$sql = "SELECT userid, name FROM user WHERE sysid = '" . $row['author'] . "';";
	try {
		$author = queryFetchArrayDB($db2, $sql);
	} catch(Exception $ex) {
		$author = null;
	}
	$db2 -> close();
	$row = array_map("stripslashes", $row);
	$dotpos = strrpos($row['headimage'], '.');
	$headimage_resized = substr($row['headimage'], 0, $dotpos) . 'x320' . substr($row['headimage'], $dotpos);
	echo '<div class="ar-thu-container">';
	echo '<div class="ar-thu">';
	echo '<div class="ar-head-thu">';
	echo '<div class="ar-meta">';
	showTimeStamp($row['timestamp']);
	echo '<h1 class="ar-title-thu">';
	echo '<a class="ajax" href="?p='.$row['id'].'">'. $row['title']. '</a></h1>';
	echo '<div style="clear: both;"></div>';
	//echo '<span class="label label-info">' . $row['timestamp'] . '</span>';
	$tags = preg_split("/\s+/", $row['tag'], -1, PREG_SPLIT_NO_EMPTY);
	echo '<span>';
	for ($i = 0; $i < count($tags); $i++) {
		echo('<a href="?tag=' . $tags[$i] . '" class="ajaxtags"><span class="badge"><i class="icon-tag icon-white"></i>' . $tags[$i] . '</span></a>');
	}
	echo '</span>';
	echo '<span>';
	if ($author == null) {
		echo '不明';
	} else {
		echo '<a href="?author=' . $author['userid'] . '" class="ajaxtags ar-author"><span class="badge badge-warning"><i class="icon-user icon-white"></i>' . $author['name'] . '</span></a>';
	}
	echo '</span></div></div>';
	
	echo '<div class="ar-main-thu">';
	echo '<span class="ar-headimage-thu"><a class="ajax" href="?p=' . $row['id'] . '"><img src="./data/' . $headimage_resized . '" /></a></span>';
	
	echo '<div class="ar-preface">';
	echo $row['preface'];
	echo '</div>';
	echo '<a class="ajax" href="?p=' . $row['id'] . '"><span>...続きを読む</span></a>';
	echo '</div>';
	//echo '<div class="ar-social-thu">';
	//showSocialButtons($row['id'], $row['title'], $name);
	//echo '</div>';
	$i++;
	$c++;
	echo '<div style="clear: both;"></div>';
	echo '</div>';
	echo '</div>';
}
$db -> close();



if ($c == $limit) {
	$ret['end'] = false;
} else {
	$ret['end'] = true;
	echo '<div class="end-of-world"><p>すべての記事を読み込みました。thx!!!🍣</p></div>';
}

$ret['echo'] = ob_get_contents();
ob_end_clean();

echo json_encode($ret);
?>