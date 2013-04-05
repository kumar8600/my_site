<style>
	dd.comment {
		overflow: hidden;
		margin-left: 0;
		border-bottom: solid 1px #888888;
	}
</style>
<div class="dark">
	<legend>
		新着コメント
	</legend>
	<?php
	require_once dirname(__FILE__) . '/../../../connect-db.php';
	function articleMeta($id) {
		$db = connectDB();
		$sql = "SELECT title, timestamp FROM article WHERE id = :id";
		$stmt = $db -> prepare($sql);
		$stmt -> bindValue(":id", $id);
		$result = $stmt -> execute();
		$row = $result -> fetchArray();
		return $row;
	}

	$db = connectCommentsDB();
	$sql = "SELECT * FROM comment ORDER BY id DESC LIMIT 5";
	$stmt = $db -> prepare($sql);
	$result = $stmt -> execute();
	echo '<dl class="comment">';
	while ($row = $result -> fetchArray()) {
		$ar_meta = articleMeta($row['articleid']);
		echo '<a href="?p='.$row['articleid'].'" class="ajax">';
		echo '<dd class="comment">';
		echo '<span class="label">' . $ar_meta['timestamp'] . '</span>';
		echo "</dd>";
		echo '<dt class="comment">';
		echo $ar_meta['title'];
		echo "</dt>";
		echo '<dd class="comment">';
		echo $row['name'] . ' : ' . $row['body'];
		echo "</dd>";
		echo '</a>';
	}
	echo "</dl>";
	?>
</div>