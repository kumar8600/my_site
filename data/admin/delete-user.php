<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$db = connectAuthDB();

$input['name'] = $_POST['name'];
$input['password'] = $_POST['password'];

array_map("ifUnSetDie", $input);

$sql = "DELETE FROM user WHERE name = '". $input['name'] ."' AND password '". $input['password'] ."');";

queryDB($db, $sql);

echo("ユーザーの削除に成功しました");

$db -> close();
?>