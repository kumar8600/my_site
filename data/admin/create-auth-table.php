<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$db = connectAuthDB();

$sql = "CREATE TABLE user (sysid INTEGER PRIMARY KEY AUTOINCREMENT, userid TEXT, password TEXT, name TEXT, email TEXT, website TEXT);";

queryDB($db, $sql);

$db -> close();
echo("認証用テーブルの作成に成功。");
?>