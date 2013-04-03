<!-- DBからタグ一覧を取得 -->
<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$sql = "SELECT * FROM aux_tag ORDER BY frequency desc LIMIT 15;";
$result = $db -> query($sql);
if (!$result) {
	die('DBとの接続に失敗。<a type="button" class="btn btn-danger" href="?admin=start-up">サイトのスタートアップをおすすめします。</a>');
}
while ($row = $result -> fetchArray()) {
	echo('<a href="?tag=' . $row['name'] . '" class="ajaxtags"><span class="badge"><i class="icon-tag icon-white"></i>' . $row['name'] . '</span></a>');
}
$db -> close();
?>