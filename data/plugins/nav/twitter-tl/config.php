<?php
require_once dirname(__FILE__) . '/../functions.php';
if ($_GET['configid'] != "") {
	$body = file_get_contents(getNavConfigDir() . basename(getcwd()) . '/' . $_GET['configid'] . '.html');
}
$name = getConfigNameById(basename(getcwd()), $_GET['configid']);
?>
<legend class="p-title">
	Twitterタイムライン <?php
	if ($name != "") {
		echo $name . ' の編集';
	} else {
		echo "の新規作成";
	}
	?>
</legend>
<form action="./data/plugins/nav/twitter-tl/insert.php" method="post" class="ajaxform">
	<div class="control-group">
		<label class="control-label" for="inputTitle">設定の名前</label>
		<div class="controls">
			<input type="text" name="title" id="inputTitle" style="font-size: 24px; height: 28px; width: 98%;" value="<?php echo $name; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputCode">ソースコード</label>
		<div class="controls">
			<textarea name="body" id="inputCode" style="width: 98%; height: 90px;" ><?php echo $body; ?></textarea>
		</div>
		<span class="help-block">
			<p>
				Twitter公式の埋め込みタイムラインはAPI1.1から、公式サイトで作られたウィジェットしか使えなくなりました。
			</p>
			<p>
				ですので、<a href="https://dev.twitter.com/ja/docs/embedded-timelines">埋め込みタイムライン | Twitter Developers</a>を参考に、<a href="https://twitter.com/settings/widgets">各アカウントのページ</a>から作って、出てきたソースコードを貼り付けてください。
			</p></span>
	</div>
	<input type="hidden"
	name="configid" value="<?php echo $_GET['configid']; ?>" />
	<hr />
	<div class="control-group">
		<div class="controls">
			<input class="btn btn-primary" type="submit" value="送信" />
		</div>
	</div>
</form>