<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';

$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];

array_map("ifUnSetDie", $input);

$db = connectAuthDB();

$input['password'] = myCrypt($input['password']);

$sql = "DELETE FROM user WHERE userid = '" . $input['userid'] . "' AND password = '" . $input['password'] . "';";

queryDB($db, $sql);

if (getSessionUser() == $input['userid'])
	sessionLogout();

echo('<meta charset="UTF-8" />ユーザーの削除に成功しました');

$db -> close();
?>