<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$input['name'] = $_POST['name'];
$input['password'] = $_POST['password'];

array_map("ifUnSetDie", $input);

$db = connectAuthDB();

$sql = "DELETE FROM user WHERE name = '". $input['name'] ."' AND password '". $input['password'] ."');";

queryDB($db, $sql);

echo("ユーザーの削除に成功しました");

$db -> close();
?>