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
$db -> close();

$ifrootid;
$ifroothelp;
if($row['userid'] == "root") {
	$ifrootid = 'readonly="readonly"';
	$ifroothelp = 'rootユーザーのidは変更できません。';
}

?>
<meta charset="UTF-8" />
<legend class="p-title">プロフィールの編集</legend>
<form method="post" action="./data/admin/set-user.php" class="form-horizontal ajaxform">
	<hr />
	<input type="hidden" name="olduserid" value="<?php echo($row['userid']) ?>" />
	<div class="control-group">
		<label class="control-label" for="inputUserId">新しいユーザID</label>
		<div class="controls">
			<input type="text" name="userid" id="inputUserId" value="<?php echo($row['userid']) ?>" <?php echo $ifrootid ?> />
			<span class="help-block">使用可能な文字列「0-9a-zA-Z_-」で、3文字以上20文字以内 <?php echo $ifroothelp ?> </span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputOldPassword">今までのパスワード</label>
		<div class="controls">
			<input type="password" name="oldpassword" id="inputOldPassword" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword">新しいパスワード</label>
		<div class="controls">
			<input type="password" name="password" id="inputPassword"/>
			<span class="help-block">使用可能な文字列「0-9a-zA-Z_-」で、8文字以上40文字以内</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPasswordRe">新しいパスワード（確認）</label>
		<div class="controls">
			<input type="password" name="password_re" id="inputPasswordRe"/>
			<span class="help-block">確認のためもう一度入力してください。</span>
		</div>
	</div>
	<hr />
	<div class="control-group">
		<label class="control-label" for="inputName">名前</label>
		<div class="controls">
			<input type="text" name="name" id="inputName" value="<?php echo($row['name']) ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputEmail">メールアドレス</label>
		<div class="controls">
			<input type="text" name="email" id="inputEmail" value="<?php echo($row['email']) ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputWebsite">自分のサイト</label>
		<div class="controls">
			<input type="text" name="website" id="inputWebsite" value="<?php echo($row['website']) ?>" />
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
<a href="?admin=delete-user" class="ajax">アカウントを削除</a>