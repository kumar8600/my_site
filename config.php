<?php
//TODO 設定情報は出来るだけ別に分けたい．適宜require_onceすれば良い．

$root_location = dirname(__FILE__);
$db_private_path = $root_location . '/' . 'db/private';
$db_path = $root_location . "/" . "db/private/article.sqlite3";
$set_db_path = $root_location . "/" . "db/private/settings.sqlite3";
$comments_db_path = $root_location . "/" . "db/private/comments.sqlite3";
$logs_db_path = $root_location . "/" . "db/private/logs.sqlite3";
$auth_db_path = $root_location . "/" . "db/private/users.sqlite3";
$plugins_path = $root_location . "/" . "data/plugins/";
$plugins_nav_path = $root_location . "/" . "data/plugins/nav/";
$plugins_config_path = $root_location . "/" . "data/plugins/config/";
$plugin_ini_name = "plugin.ini";
$config_folder_name = "config";
$salt = '$5$rounds=5000$ultimateUNKO$';
$timezone = 'Asia/Tokyo';
$preface_length = 100;
?>