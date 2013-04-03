<?php
require_once dirname(__FILE__) . '/functions.php';
require_once dirname(__FILE__) . '/../../config.php';
require_once dirname(__FILE__) . '/../admin/session.php';

if(!isRootUser()) {
	die("rootユーザーとしてログインしてください。");
}

$folder = $_POST['folder'];
$id = $_POST['id'] ;
if($folder == "" || $id == "") {
	var_dump($_POST);
	die("値が足りません。");
}

if(deleteConfig($folder, $id)) {
	echo "OK: 設定を削除しました。";
} else {
	echo "設定の削除に失敗。設定ファイルのパーミッションを見なおしてください。";
}
?>