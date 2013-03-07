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
		// 通常のテーブルを作る
		$sql = "create table article (id integer primary key autoincrement, timestamp default(datetime('now', 'localtime')), title, body, thumbbody, headimage);";
		$result = $db -> query($sql);

		if (!$result) {
			die('通常のテーブル作成クエリーに失敗: ' . $sqlerror);
		}
		print('通常テーブルの作成に成功。');
		// タグ検索用のテーブルを作る
		$sql = "create virtual table fts_tag using fts4(tag text);";
		$result = $db -> query($sql);

		if (!$result) {
			die('タグ検索テーブル作成クエリーに失敗: ' . $sqlerror);
		}
		print('タグ検索テーブルの作成に成功。');

		// タグ検索用のテーブルを閲覧するテーブルを作る
		$sql = "create virtual table aux_article using fts4aux(fts_tag);";
		$result = $db -> query($sql);

		if (!$result) {
			die('タグ検索テーブルを閲覧するテーブル作成クエリーに失敗: ' . $sqlerror);
		}
		print('タグ検索テーブルを閲覧するテーブルの作成に成功。');

		$db -> close;

		print('切断しました。<br>');
		?>
		<a href="../">トップページへ</a>
	</body>
</html>
