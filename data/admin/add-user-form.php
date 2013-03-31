<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/session.php';
if (!isRootUser()) {
	if (!isRootExists() || !canRegister()) {
		die("新規登録は許可されていません。");
	}
}
?>
<meta charset="UTF-8" />
<legend class="p-title">新規登録</legend>
<form method="post" action="./data/admin/add-user.php" class="form-horizontal ajaxform">
	<hr />
	<div class="control-group">
		<label class="control-label" for="inputUserId">ユーザID</label>
		<div class="controls">
			<input type="text" name="userid" id="inputUserId" value="<?php echo($row['userid']) ?>" />
			<span class="help-block">使用可能な文字列「0-9a-zA-Z_-」で、3文字以上20文字以内</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPassword">パスワード</label>
		<div class="controls">
			<input type="password" name="password" id="inputPassword"/>
			<span class="help-block">使用可能な文字列「0-9a-zA-Z_-」で、8文字以上40文字以内</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputPasswordRe">パスワード（確認）</label>
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
			<input class="btn" type="submit" value="登録" />
		</div>
	</div>
</form>