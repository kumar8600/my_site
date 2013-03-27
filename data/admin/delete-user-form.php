<?php
require_once dirname(__FILE__) . '/session.php';

$sesuserid = $GLOBALS['ses_sysid'];
if ($ses_sysid == "") {
	$ses_sysid = getSessionSysId();
}
if ($ses_sysid == "") {
	die('ログインしてください');
}

?>
<meta charset="UTF-8" />
<h3>本当にアカウントを削除しますか？</h3>
<div class="alert alert-error"><strong>注意!</strong> あなたのプロフィール、記事がすべて削除されます。</div>
<form method="post" action="./data/admin/delete-user.php" class="ajaxform">
	<input type="hidden" name="sysid" value="<?php echo($ses_sysid) ?>" />
	パスワード<br />
	<input type="password" name="password" />
	<br />
	<input class="btn btn-danger" type="submit" value="削除" />
</form>