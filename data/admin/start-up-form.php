<meta charset="UTF-8" />
<head>
	<title>CMSのスタートアップ</title>
</head>
<?php
require_once dirname(__FILE__) . '/../connect-db.php';

// rootユーザーが存在する場合、スタートアップは出来ない。
if(isRootExists()) {
	die("既にスタートアップは完了しています。");
}
?>
<h2>CMSのスタートアップ</h2>
<p>
	CMSのスタートアップへようこそ。ここでは、rootユーザーの作成、サイトの設定、データベースの初期化を行います。スタートアップが完了すれば、すぐにサイトに記事を追加できるようになります。
</p>
<hr />
<hr />
<form method="post" action="./admin/start-up.php" class="form-horizontal ajaxform">
	<h3>rootユーザーの作成</h3>
	<p>
		このCMSはマルチユーザー対応です。ユーザー管理をするためにまず、特権を持つrootユーザーをつくりましょう。
	</p>
	<hr />
	<div class="control-group">
		<label class="control-label" for="inputUserId">ユーザID</label>
		<div class="controls">
			<input type="text" name="userid" id="inputUserId" value="root" readonly="readonly" />
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
			<input type="text" name="name" id="inputName" />
			<span class="help-block">実際に表示される名前です。</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputEmail">メールアドレス</label>
		<div class="controls">
			<input type="text" name="email" id="inputEmail" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputWebsite">自分のサイト</label>
		<div class="controls">
			<input type="text" name="website" id="inputWebsite" />
			<span class="help-block">必須ではありません。</span>
		</div>
	</div>
	<hr />
	<hr />
	<h3>サイトの設定</h3>
	<p>
		このサイトのタイトルと説明文を設定します。
	</p>
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
	<div class="control-group">
		<label class="control-label">誰でも新規登録</label>
		<div class="controls">
			<label class="radio">
				<input type="radio" name="site_regist" id="optionsRadios1" value="true" >
				許可する</label>
			<label class="radio">
				<input type="radio" name="site_regist" id="optionsRadios2" value="false" checked>
				許可しない</label>
			<span class="help-block"> 許可しない場合、rootユーザーの「ユーザー管理」からのみ新規登録が可能です。 </span>
		</div>
	</div>
	<hr />
	<div class="control-group">
		<div class="controls">
			<input class="btn btn-primary btn-large" type="submit" value="スタートアップを完了" />
		</div>
	</div>
</form>
<hr />
