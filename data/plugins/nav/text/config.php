<?php
require_once dirname(__FILE__) . '/../functions.php';
if($_GET['configid'] != "") {
	$body = file_get_contents(getNavConfigDir() . basename(getcwd()) . '/' . $_GET['configid'] . '.html');
}
$name = getConfigNameById(basename(getcwd()), $_GET['configid']);
?>
<legend class="p-title">テキストウィジェット
	<?php
	if($name != "") {
		echo $name . ' の編集';
	} else {
		echo "の新規作成";
	}
	
	?>
	</legend>
<form action="./data/plugins/nav/text/insert.php" method="post" class="editorform">
	<div class="control-group">
		<label class="control-label" for="inputTitle">設定の名前</label>
		<div class="controls">
			<input type="text" name="title" id="inputTitle" style="font-size: 24px; height: 28px; width: 98%;" value="<?php echo $name; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="editor">本文</label>
		<div class="controls">
			<textarea name="body" id="editor" ><?php echo $body; ?></textarea>
		</div>
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
<script src="./js/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	CKEDITOR.replace('editor');
</script>