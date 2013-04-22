<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/../delete-article-func.php';

$_POST = array_map("strip_tags", $_POST);
$input['sysid'] = $_POST['sysid'];
$input['password'] = $_POST['password'];

ifUnSetDie($input['sysid']);
if($input['sysid'] == getSysIdByDB("root")) {
	die("rootユーザーを削除することはできません。");
}

$sesuserid = $GLOBALS['sesuserid'];
if ($sesuserid == "") {
	$sesuserid = getSessionUser();
}
// rootユーザーは特権でパスワード認証なし
if ($sesuserid != "root") {
	ifUnSetDie($input['password']);
	try {
		authorizeSysId($input['sysid'], $input['password']);
	} catch(Exception $ex) {
		die("パスワードが間違っています。");
	}
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

$stmt = $db -> prepare("DELETE FROM user WHERE sysid = :sysid");
$stmt -> bindValue(':sysid', $input['sysid'], SQLITE3_INTEGER);
$result = $stmt -> execute();
$db -> close();

if (getSessionSysId() == $input['sysid'])
	sessionLogout();

echo('OK: <meta charset="UTF-8" />ユーザーの削除に成功しました');
?>