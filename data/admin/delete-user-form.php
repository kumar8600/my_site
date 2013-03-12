<?php
require_once dirname(__FILE__) . '/session.php';

$sesuserid = $GLOBALS['sesuserid'];
if ($sesuserid == "") {
	$sesuserid = getSessionUser();
}
if ($sesuserid == "") {
	die('ログインしてください');
}

$db = connectAuthDB();

$sql = "SELECT sysid, userid, name, email, website FROM user WHERE userid = '" . $sesuserid . "';";
$row = queryFetchArrayDB($db, $sql);
?>
<meta charset="UTF-8" />
<h3>本当にアカウントを削除しますか？</h3>
<form method="post" action="<?php echo dirname(__FILE__) ?>/delete-user.php">
	<input type="hidden" name="userid" value="<?php echo($row['userid']) ?>" />
	パスワード<br />
	<input type="password" name="password" />
	<br />
	<input class="btn btn-danger" type="submit" value="削除" />
</form>