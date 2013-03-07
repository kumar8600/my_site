<?php
require_once dirname(__FILE__) . '/../config.php';
function connectDB() {
	$db = new SQLite3($GLOBALS['db_path']);
	return $db;
}
?>