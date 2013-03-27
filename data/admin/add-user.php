<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/is-string-safe.php';

$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
$input['name'] = $_POST['name'];
$input['email'] = $_POST['email'];

array_map("ifUnSetDie", $input);
$input['website'] = $_POST['website'];

isPostSafe($input);

$input['password'] = myCrypt($input['password']);

$db = connectAuthDB();

// 同じIDを持つアカウントがないかチェックする
$sql = "SELECT userid FROM user WHERE userid = '" . $input['userid'] . "';";
if (isExistDB($db, $sql))
	die("既に同じユーザーIDを持つアカウントがあります。違うIDに変更してください");

array_map(array($db, 'escapeString'), $input);

$sql = "INSERT INTO user (userid, password, name, email, website) VALUES('" . $input['userid'] . "', '" . $input['password'] . "', '" . $input['name'] . "', '" . $input['email'] . "', '" . $input['website'] . "');";

queryDB($db, $sql);

$db -> close();

setSessionUser($input['userid']);

echo("OK: ユーザーの追加に成功。");
?>