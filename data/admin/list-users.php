<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';

// rootユーザーとしてログインしてるかチェック
$sesuserid = $GLOBALS['sesuserid'];
if ($sesuserid == "") {
	$sesuserid = getSessionUser();
}
if ($sesuserid != "root") {
	die('rootユーザーとしてログインしてください。');
}

$db = connectAuthDB();

$sql = "SELECT sysid, userid, name, email, website FROM user;";
$result = queryDB($db, $sql);

echo '<h3>ユーザー管理</h3>';
echo '<table class="table table-hover"><thead><tr><td>#</td><td>ユーザID</td><td>ユーザ名</td><td>email</td><td>サイト</td><td>操作</td></tr></thead><tbody>';
while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
	echo '<tr>';
	foreach ($row as $key => $value) {
		echo '<td>';
		echo $value;
		echo '</td>';
	}
	echo '<td>';
	if ($row['userid'] != "root")
		echo '<a class="ajax" href="?admin=delete-user&sysid=' . $row['sysid'] . '">削除</a>';
	echo '</td>';
	echo '</tr>';
}
echo '</tbody></table>';
?>
<p>
	<a href="?admin=add-user">ユーザーを追加</a>
</p>