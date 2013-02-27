<html>
	<head>
		<title>新しい記事の作成</title>
	</head>
	<body>
		<form action="./insert-article.php" method="post">
			タイトル:
			<input type="text" name="title" />
			<br />
			本文:
			<TEXTAREA cols="40" rows="6" wrap="off" name="body"></TEXTAREA>
			<br />
			タグ:
			<input type="text" name="tag" />
			<br />
			<input type="submit" name="submit1" value="送信" />
		</form>

	</body>
</html>