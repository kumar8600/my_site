<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';

// rootユーザーとしてログインしてるかチェック
$sesuserid = $GLOBALS['sesuserid'];
if ($sesuserid == "") {
	$sesuserid = getSessionUser();
}
if ($sesuserid != "root") {
	die('rootユーザーとしてログインしてください。');
}

$input_name = $_POST['site_name'];
$input_desc = $_POST['site_desc'];
ifUnSetDie($input_name);

$db = connectSettingsDB();

$input_name = htmlspecialchars($input_name, ENT_QUOTES);
$input_desc = htmlspecialchars($input_desc, ENT_QUOTES);

// サイトの設定が既に存在するか調べる。なければINSERT
$sql = "SELECT COUNT(id) FROM site;";
$row = $db -> querySingle($sql);
if($row == 0) {
	$stmt = $db -> prepare("INSERT INTO site(id, name, description) VALUES(1, :name, :desc)");
} else {
	$stmt = $db -> prepare("UPDATE site SET name = :name, description = :desc WHERE id = 1");
}

$stmt -> bindValue(":name", $input_name, SQLITE3_TEXT);
$stmt -> bindValue(":desc", $input_desc, SQLITE3_TEXT);

$result = $stmt -> execute();
$db -> close();

if (!$result) {
	die("サイトの情報の更新に失敗");
} else {
	echo " OK: サイトの情報を更新した";
}
?>