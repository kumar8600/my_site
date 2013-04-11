<div class="dark" style="height: 200px">
<?php
require_once dirname(__FILE__) . '/../functions.php';
echo '<legend>タグ</legend>';
$db = connectDB();

$sql = "SELECT * FROM aux_tag ORDER BY frequency desc LIMIT 20;";
$result = $db -> query($sql);
if (!$result) {
	die('DBとの接続に失敗。');
}
while ($row = $result -> fetchArray()) {
	echo('<a href="?tag=' . $row['name'] . '" class="ajaxtags"><span class="badge"><i class="icon-tag icon-white"></i>' . $row['name'] . '</span></a>');
}
$db -> close();
?>
</div>