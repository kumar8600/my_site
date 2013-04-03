<?php
require_once dirname(__FILE__) . '/functions.php';
require_once dirname(__FILE__) . '/../admin/session.php';

if(!isRootUser()) {
	die("rootユーザーとしてログインしてください。");
}
$folder = $_GET['folder'];
$id = $_GET['configid'];
if($folder == "" || $id == "") {
	var_dump($_GET);
	die("値が足りません。");
}
?>
<legend class="p-title">設定の削除</legend>
<h3>本当に<?php echo(getNameByFolder($folder)); ?>の設定「<?php echo(getConfigNameById($folder, $id)); ?>」
を削除しますか？</h3>
<div class="alert alert-error">
	<strong>注意!</strong> 削除した設定は元に戻せません。
</div>
<form method="post" action="./data/nav/delete-conf.php" class="ajaxform">
	<input type="hidden" name="folder" value="<?php echo $folder; ?>" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input class="btn btn-danger" type="submit" value="削除" />