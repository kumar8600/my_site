<html>
	<head>
		<title>テーブル作成</title>
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

		// SQLiteに対する処理
		$sql = "create virtual table article using fts4(id integer primary key, timestamp default (datetime('now', 'localtime')), tag, title, body, thumbbody, headimage);";
		$result = $db -> query($sql);

		if (!$result) {
			die('テーブル作成クエリーに失敗: ' . $sqlerror);
		}
		print('テーブルの作成に成功。');

		$db -> close;

		print('切断しました。<br>');
	?>
</body>
</html>