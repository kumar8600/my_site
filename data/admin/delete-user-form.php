<?php
require_once dirname(__FILE__) . '/session.php';

$ses_sysid = $GLOBALS['ses_sysid'];
if ($ses_sysid == "") {
	$ses_sysid = getSessionSysId();
}
if ($ses_sysid == "") {
	die('ログインしてください');
}
$del_sysid = $ses_sysid;
$root_sysid = getSysIdByDB("root");
if ($ses_sysid == $root_sysid) {
	if ($_GET['sysid'] == "" || $_GET['sysid'] == $root_sysid) {
		die("rootユーザーを削除することはできません。");
	}
	$del_sysid = $_GET['sysid'];
	$root = true;
}
?>
<meta charset="UTF-8" />
<h3>本当にアカウント「<?php echo(getUserIdByDB($del_sysid)); ?>」
を削除しますか？</h3>
<div class="alert alert-error">
	<strong>注意!</strong> プロフィール、記事がすべて削除されます。
</div>
<form method="post" action="./data/admin/delete-user.php" class="ajaxform">
	<input type="hidden" name="sysid" value="<?php echo($del_sysid) ?>" />
	<?php
		if ($root == false) {
			echo 'パスワード
<br />
<input type="password" name="password" />';
		}
	?>
	<br />
	<input class="btn btn-danger" type="submit" value="削除" />
</form>