<?php
require_once dirname(__FILE__) . '/../functions.php';
$name = $_POST['title'];
$body = $_POST['body'];
$configid = $_POST['configid'];
if ($configid == "") {
	$max = getMaxNumInFolder(getNavConfigDir() . basename(dirname(__FILE__)).'/', '/^[0-9]+-conf.ini$/');
	$configid = $max + 1;
}

if ($name == "" || $body == "") {
	die("値が足りません。");
}
$desc = 'Twitter公式サイトで作られたウィジェット';

$arr = array('name' => $name, 'desc' => $desc);
if (!arrayToIni($arr, getNavConfigDir() . basename(getcwd()) . '/' . $configid . '-conf.ini')) {
	die("iniファイルの書き込みに失敗。");
}

if (!file_put_contents(getNavConfigDir() . basename(getcwd()) . '/' . $configid . '.html', $body)) {
	die("htmlファイルの書き込みに失敗。");
}

echo "SUCCESS: 設定" . $name . "を保存しました。";
?>