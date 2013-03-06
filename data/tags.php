<!-- DBからタグ一覧を取得 -->
<ul class="nav nav-list">
	<?php
	$db = new SQLite3('../article.sqlite3');

	$sql = "select term from aux_article where col = '*' order by documents desc limit 15;";
	$result = $db -> query($sql);
	if (!$result) {
		die('DBとの接続に失敗。<a type="button" class="btn btn-danger" href="data/create-table.php">DBの初期化をおすすめします。</a>');
	}
	while ($row = $result -> fetchArray()) {
		echo('
				<li>
				<a href="?tag=' . $row['term'] . '" class="ajaxtags">' . $row['term'] . '</a>
				</li>');
	}
	$db -> close();
	?>
</ul>
