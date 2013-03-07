<!-- DBからタグ一覧を取得 -->
<ul class="nav nav-list">
	<?php
	require_once dirname(__FILE__) . '/../config.php';
	$db = new SQLite3($GLOBALS['db_path']);

	$sql = "SELECT term FROM aux_article WHERE col = '*' ORDER BY documents desc LIMIT 15;";
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
