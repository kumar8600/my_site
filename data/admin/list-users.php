<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';

$db = connectAuthDB();

$sql = "SELECT sysid, userid, name, email, website FROM user;";
$result = queryDB($db, $sql);

echo '<table class="table table-hover"><thead><tr><td>#</td><td>ユーザID</td><td>ユーザ名</td><td>email</td><td>サイト</td><td>操作</td></tr></thead><tbody>';
while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
	echo '<tr>';
	foreach ($row as $key => $value) {
		echo '<td>';
		echo $value;
		echo '</td>';
	}
	echo '<td>';
	echo '<a class="ajax" href="?admin=set-user">設定</a>&nbsp;<a class="ajax" href="?admin=delete-user">削除</a>';
	echo '</td>';
	echo '</tr>';
}
echo '</tbody></table>';
?>