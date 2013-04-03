<form class="form-inline">
  <input type="text" class="input-small" name="userid" placeholder="ユーザID">
  <input type="password" class="input-small" name="password" placeholder="パスワード">
  <input type="submit" class="btn" value="サインイン"/>
</form>
<?php
	require_once dirname(__FILE__) . '/../connect-db.php';
	if(isRootExists() && canRegister()) {
		echo '<a href="?admin=add-user" class="ajax" data-dismiss="modal">新規登録</a>';
	}
?>