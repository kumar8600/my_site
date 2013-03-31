<script src="./js/ckeditor/ckeditor.js"></script>
<script src="./js/jquery.upload-1.0.2.min.js"></script>
<script type="text/javascript">
	$('input[type=file]').change(function() {
		$(this).upload('./data/upload-image.php', function(res) {
			var dotpos = res['filename'].indexOf('.');
			var img_mini = res['filename'].substring(0, dotpos) + 'x320' + res['filename'].substring(dotpos);
			$('#thumb').html(res['error']);
			$('#thumb').html('<img src="' + 'data/' + img_mini + '" />');
			$('input[name=headimage]').attr("value", res['filename']);
		}, 'json');
	});
	CKEDITOR.replace('editor');
</script>
<?php
require_once dirname(__FILE__) . '/connect-db.php';
// GET変数に"p"があるなら、編集。でなければ追加を行う。
if ($_GET['p'] != "") {
	$db = connectDB();
	$stmt = $db -> prepare("SELECT * FROM article WHERE id = :id");
	$stmt -> bindValue(":id", $_GET['p'], SQLITE3_INTEGER);
	$result = $stmt -> execute();
	$row = $result -> fetchArray();
	$row = array_map("stripslashes", $row);
	$row['title'] = htmlspecialchars_decode($row['title']);
	$row['tag'] = preg_replace("/\s+/", " ", $row['tag']);
	$row['tag'] = preg_replace("/(^ +| +$)/", "", $row['tag']);

	$title = "記事「" . $row['title'] . "」の編集";
} else {
	$title = "記事の追加";
}
?>
<legend class="p-title"><?php echo $title; ?></legend>
<div class="control-group">
	<label class="control-label" for="fileHeadImage">サムネイル</label>
	<div class="controls">
		<input type="file" name="userfile" id="fileHeadImage" />
	</div>
</div>
<br />
<form action="./data/insert-article.php" method="post" class="editorform">
	<div id="thumb"><?php
	if ($row['headimage'] != "") {
		$filepath = $row['headimage'];
		$dotpos = strrpos($filepath, '.');
		$mini_image = substr($filepath, 0, $dotpos) . 'x320' . substr($filepath, $dotpos);
		echo '<img src="data/' . $mini_image . '" />';
	}
 ?></div>
	<input type="hidden" name="headimage"
	value="<?php echo $row['headimage']; ?>" />
	<div class="control-group">
		<label class="control-label" for="inputTitle">タイトル</label>
		<div class="controls">
			<input type="text" name="title" id="inputTitle" style="font-size: 24px; height: 28px; width: 98%;" value="<?php echo $row['title']; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="editor">本文</label>
		<div class="controls">
			<textarea name="body" id="editor" ><?php echo $row['body']; ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputTag">タグ</label>
		<div class="controls">
			<input type="text" name="tag" id="inputTag" value="<?php echo $row['tag'] ?>" />
		</div>
	</div>
	<input type="hidden"
	name="rowid" value="<?php echo $row['id']; ?>" />
	<input type="hidden"
	name="author" value="<?php echo $row['author']; ?>" />
	<hr />
	<div class="control-group">
		<div class="controls">
			<input class="btn btn-primary" type="submit" value="送信" />
		</div>
	</div>
</form>