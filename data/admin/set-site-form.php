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
<legend class="p-title">サイトの設定</legend>
<p>
	このサイトのタイトルと説明文を設定します。
</p>
<form method="post" action="./data/admin/set-site.php" class="form-horizontal ajaxform">
	<hr />

	<div class="control-group">
		<label class="control-label" for="inputSiteName">サイトの名前</label>
		<div class="controls">
			<input type="text" name="site_name" id="inputSiteName" value="<?php echo $row['name']; ?>" />
			<span class="help-block"> タイトルに使われます。 </span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputSiteDesc">サイトの説明</label>
		<div class="controls">
			<input type="text" name="site_desc" id="inputSiteDesc" value="<?php echo $row['description']; ?>" />
			<span class="help-block"> タイトルの横に表示されます。 </span>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label">誰でも新規登録</label>
		<div class="controls">
			<label class="radio">
				<input type="radio" name="site_regist" id="optionsRadios1" value="true" <?php
				if($row['allowregist'] == 1) {
					echo "checked";
				}
				?>>
				許可する</label>
			<label class="radio">
				<input type="radio" name="site_regist" id="optionsRadios2" value="false" <?php
				if($row['allowregist'] == 0) {
					echo "checked";
				}
				?>>
				許可しない</label>
			<span class="help-block"> 許可しない場合、rootユーザーの「ユーザー管理」からのみ新規登録が可能です。 </span>
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
