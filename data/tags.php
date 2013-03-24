<!-- DBからタグ一覧を取得 -->
<ul class="nav nav-list">
	<?php
	require_once dirname(__FILE__) . '/connect-db.php';
	$db = connectDB();

	$sql = "SELECT * FROM aux_tag ORDER BY frequency desc LIMIT 15;";
	$result = $db -> query($sql);
	if (!$result) {
		die('DBとの接続に失敗。<a type="button" class="btn btn-danger" href="data/create-table.php">DBの初期化をおすすめします。</a>');
	}
	while ($row = $result -> fetchArray()) {
		echo('
				<li>
				<a href="?tag=' . $row['name'] . '" class="ajaxtags">' . $row['name'] . '</a>
				</li>');
	}
	$db -> close();
	?>
</ul>
