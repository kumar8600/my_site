<html>
	<head>
		<title>新しい記事を挿入</title>
	</head>
	<body>

		<?php

		try {
			$db = new SQLite3('article.db');
		} catch(Exception $e) {
			echo 'DBとの接続に失敗';
			die($e -> getTraceAsString());
		}

		print('接続に成功しました。<br>');

		$input_tag = sqlite_escape_string($_POST['tag']);
		$input_title = sqlite_escape_string($_POST['title']);
		$input_body = sqlite_escape_string($_POST['body']);
		// SQLiteに対する処理
		$sql = "insert into article (tag, title, body) values('$input_tag', '$input_title', '$input_body');";
		$result = $db->query($sql);

		if (!$result) {
			die('インサートクエリーに失敗: ' . $sqlerror);
		}
		print('記事の作成に成功。');

		$db->close();

		print('切断しました。<br>');
	?>
</body>
</html>