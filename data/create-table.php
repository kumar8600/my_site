<html>
	<head>
		<meta charset="UTF-8">
		<title>テーブル作成</title>
	</head>
	<body>
		<?php
		require_once dirname(__FILE__) . '/connect-db.php';
		$db = connectDB();
		// SQLiteに対する処理
		// 記事テーブルを作る
		$sql = "CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp DEFAULT(datetime('now', 'localtime')), title, body, thumbbody, headimage, tag, author);";
		$result = $db -> query($sql);

		if (!$result) {
			die('記事テーブル作成クエリーに失敗: ' . $sqlerror);
		}
		print('記事テーブルの作成に成功。');
		
		// タグ補助用テーブルを作る
		$sql = "CREATE TABLE aux_tag (name TEXT, frequency INTEGER DEFAULT 1);";
		$result = $db -> query($sql);

		if (!$result) {
			die('タグ補助テーブル作成クエリーに失敗: ' . $sqlerror);
		}
		print('タグ補助テーブルの作成に成功。');
		
		$db -> close;
		
		// ユーザー管理用テーブルを作る
		require dirname(__FILE__) . '/admin/create-auth-table.php';
		
		print('完了しました。<br>');
		?>
		<a href="../">トップページへ</a>
	</body>
</html>
