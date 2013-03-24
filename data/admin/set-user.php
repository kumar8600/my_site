<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/is-string-safe.php';

$input['olduserid'] = $_POST['olduserid'];
$input['oldpassword'] = $_POST['oldpassword'];
$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
$input['name'] = $_POST['name'];
$input['email'] = $_POST['email'];

//フォームには予め値を入力済みな予定なので、ウェブサイト以外の項目をチェックする
array_map("ifUnSetDie", $input);
$input['website'] = $_POST['website'];

isPostSafe($input);

try{
	authorize($input['olduserid'], $input['oldpassword']);
}catch(Exception $ex) {
	die ("パスワードに間違いがあります。");
}


$input['oldpassword'] = myCrypt($input['oldpassword']);
$input['password'] = myCrypt($input['password']);


$db = connectAuthDB();

// 同じIDを持つアカウントがないかチェックする
$sql = "SELECT userid FROM user WHERE userid = '" . $input['userid'] . "' AND userid != '" . $input['olduserid'] . "';";
if (isExistDB($db, $sql))
	die("既に同じユーザーIDを持つアカウントがあります。違うIDに変更してください");

array_map(array($db, 'escapeString'), $input);

$sql = "UPDATE user SET userid = '". 
$input['userid'] .
"', password = '". 
$input['password'] .
"', name = '". 
$input['name'] .
"', email = '". 
$input['email'] .
"', website = '". 
$input['website'] ."' WHERE userid = '". $input['olduserid'] ."' AND password = '". $input['oldpassword'] ."';";

queryDB($db, $sql);

$db -> close();
//セッション情報を更新する
setSession("userid", $input['userid']);
echo("OK: ユーザーの設定変更に成功。");
?>