<?php

		try {
			$db = new SQLite3('article.db');
		} catch(Exception $e) {
			echo 'DBとの接続に失敗';
			die($e -> getTraceAsString());
		}

		print('接続に成功しました。<br>');

		$input_id = $_GET['id'];
		// SQLiteに対する処理
		$sql = "select * from article where rowid = $input_id;";
		$result = $db->query($sql);
		if (!$result) {
			die('読み込みに失敗: ' . $sqlerror);
		}
		$data_article = $result->fetchArray();
		print('記事の読み込みに成功。');
		echo $data_article['datetime'];
		echo $data_article['tag'];
		echo '<h1>', $data_article['title'], '</h1>';
		echo $data_article['body'];

		$db->close();

		print('切断しました。<br>');
	?>
<h1></h1>