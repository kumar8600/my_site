<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/../delete-article-func.php';

$input['sysid'] = $_POST['sysid'];
$input['password'] = $_POST['password'];

array_map("ifUnSetDie", $input);

try {
	authorizeSysId($input['sysid'], $input['password']);
} catch(Exception $ex) {
	die("パスワードが間違っています。");
}

// 削除するユーザーの書いたすべての記事を削除する。タグも削除するので少し周りくどい
$db = connectDB();
$sql = "SELECT id FROM article WHERE author = '" . $input['sysid'] . "';";
$result = queryDB($db, $sql);
$ids;
while ($row = $result -> fetchArray()) {
	$ids[] = $row['id'];
}
$db -> close();
if (count($ids) > 0) {
	foreach ($ids as $temp) {
		deleteArticle($temp, $input['sysid']);
	}
}

// ユーザー情報を削除する。
$db = connectAuthDB();

$input['password'] = myCrypt($input['password']);
array_map(array($db, 'escapeString'), $input);
$sql = "DELETE FROM user WHERE sysid = '" . $input['sysid'] . "' AND password = '" . $input['password'] . "';";

$result = queryDB($db, $sql);
$db -> close();

if (getSessionSysId() == $input['sysid'])
	sessionLogout();

	echo('OK: <meta charset="UTF-8" />ユーザーの削除に成功しました');
?>