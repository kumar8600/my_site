<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$db = connectAuthDB();

$input['olduserid'] = $_POST['olduserid'];
$input['oldpassword'] = $_POST['oldpassword'];
$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
$input['name'] = $_POST['name'];
$input['email'] = $_POST['email'];

//フォームには予め値を入力済みな予定なので、ウェブサイト以外の項目をチェックする
array_map("ifUnSetDie", $input);
$input['website'] = $_POST['website'];

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
echo("ユーザーの設定変更に成功。");
?>