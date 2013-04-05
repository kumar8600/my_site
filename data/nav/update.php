<?php
require_once dirname(__FILE__) . '/functions.php';
require_once dirname(__FILE__) . '/../../config.php';
require_once dirname(__FILE__) . '/../admin/session.php';

if(!isRootUser()) {
	die("rootユーザーとしてログインしてください。");
}

$folder_arr = $_POST['id'];
if($folder_arr[0] == "") {
	updateNavOrder();
	die("OK: 設定の変更に成功。");
}
foreach ($folder_arr as $folder) {
	$sharppos = strrpos($folder, '#');
	$configid = substr($folder, $sharppos + 1);
	$folder = substr($folder, 0, $sharppos);
	$ini_file = $GLOBALS['plugins_nav_path'] . $folder . '/' . $GLOBALS['plugin_ini_name'];
	$ini = parse_ini_file($ini_file);
	if($ini['page'] == "") {
		die("plugin.iniファイルが不正です。");
	}
	$order[] = array("folder" => $folder, "page" => $ini['page'], "configid" => $configid);
}

$ret = updateNavOrder($order);
if($ret) {
	echo "OK: 設定の変更に成功。";
} else {
	die("設定の変更に失敗。");
}
?>