<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';
$_POST = array_map("strip_tags", $_POST);
// rootユーザーとしてログインしてるかチェック
if (isset($GLOBALS['sesuserid'])) {
	$sesuserid = $GLOBALS['sesuserid'];
} else {
	$sesuserid = getSessionUser();
}
if ($sesuserid != "root") {
	die('rootユーザーとしてログインしてください。');
}

$input_name = $_POST['site_name'];
$input_desc = $_POST['site_desc'];
ifUnSetDie($input_name);
if($_POST['site_regist'] == "true") {
	$input_regist = 1;
} else {
	$input_regist = 0;
}

$db = connectSettingsDB();

$input_name = htmlspecialchars($input_name, ENT_QUOTES);
$input_desc = htmlspecialchars($input_desc, ENT_QUOTES);

// サイトの設定が既に存在するか調べる。なければINSERT
$sql = "SELECT COUNT(id) FROM site;";
$row = $db -> querySingle($sql);
if($row == 0) {
	$stmt = $db -> prepare("INSERT INTO site(id, name, description, allowregist) VALUES(1, :name, :desc, :regist)");
} else {
	$stmt = $db -> prepare("UPDATE site SET name = :name, description = :desc, allowregist = :regist WHERE id = 1");
}

$stmt -> bindValue(":name", $input_name, SQLITE3_TEXT);
$stmt -> bindValue(":desc", $input_desc, SQLITE3_TEXT);
$stmt -> bindValue(":regist", $input_regist, SQLITE3_INTEGER);

$result = $stmt -> execute();
$db -> close();

if (!$result) {
	die("サイトの情報の更新に失敗");
} else {
	echo " OK: サイトの情報を更新した";
}
?>