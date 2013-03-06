<?php

		try {
			$db = new SQLite3('article.sqlite3');
		} catch(Exception $e) {
			echo 'DBとの接続に失敗';
			die($e -> getTraceAsString());
		}

		$input_id = $db -> escapeString($_POST['id']);
		// SQLiteに対する処理
		$sql = "delete from article where rowid = $input_id;";
		$sql2 = "delete from fts_tag where fts_tag.docid = $input_id;";
		$result = $db->query($sql);
		if (!$result) {
			die('削除に失敗: ' . $sqlerror);
		}
		$result = $db->query($sql2);
		if (!$result) {
			die('タグ削除に失敗: ' . $sqlerror);
		}
		echo('記事の削除に成功。');
		
		$db->close();

		print('切断しました。<br>');
	?>