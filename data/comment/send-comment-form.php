<?php
if($_GET['p'] == "") {
	die("内部エラー。コメントの投稿はできません。");
}
?>
<form method="post" action="./data/comment/send-comment.php" class="commentform">
	<input type="hidden" name="p" value="
	<?php
	echo $_GET['p'];
	?>
	" />
	<table class="comment">
		<tr>
			<th>名前</th>
			<td><input type="text" name="name" id="inputName" value="名無し" /></td>
		</tr>
		<tr>
			<th>メールアドレス</th>
			<td><input type="text" name="email" id="inputEmail" value="sage" /></td>
		</tr>
		<tr>
			<th>コメント</th>
			<td><textarea name="body" id="inputBody"></textarea></td>
		</tr>
		<tr>
			<th></th>
			<td><input class="btn btn-primary" type="submit" value="投稿" /></td>
		</tr>
	</table>
</form>