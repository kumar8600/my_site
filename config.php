<?php
//TODO 設定情報は出来るだけ別に分けたい．適宜require_onceすれば良い．

$root_location = dirname(__FILE__);
$db_path = $root_location . "/" . "db/article.sqlite3";
$auth_db_path = $root_location . "/" . "shadow/users.sqlite3";
$salt = "$5$rounds=5000$ultimateUNKO$";
?>