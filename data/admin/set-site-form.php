<?php
require_once dirname(__FILE__) . '/session.php';

// rootユーザーとしてログインしてるかチェック
$sesuserid = $GLOBALS['sesuserid'];
if ($sesuserid == "") {
	$sesuserid = getSessionUser();
}
if ($sesuserid != "root") {
	die('rootユーザーとしてログインしてください。');
}

$db = connectSettingsDB();

$sql = "SELECT * FROM site WHERE id = 1;";
$result = queryDB($db, $sql);
$row;
if ($result)
	$row = $result -> fetchArray();
$db -> close();
?>
<meta charset="UTF-8" />
<h3>サイトの設定</h3>
<p>
	このサイトのタイトルと説明文を設定します。
</p>
<form method="post" action="./data/admin/set-site.php" class="form-horizontal ajaxform">
	<hr />

	<div class="control-group">
		<label class="control-label" for="inputSiteName">サイトの名前</label>
		<div class="controls">
			<input type="text" name="site_name" id="inputSiteName" />
			<span class="help-block"> タイトルに使われます。 </span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputSiteDesc">サイトの説明</label>
		<div class="controls">
			<input type="text" name="site_desc" id="inputSiteDesc" />
			<span class="help-block"> タイトルの横に表示されます。 </span>
		</div>
	</div>
	<hr />
	<div class="control-group">
		<div class="controls">
			<input class="btn" type="submit" value="変更を保存" />
		</div>
	</div>
</form>
<hr />
