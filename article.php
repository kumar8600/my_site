<?php

try {
	$db = new SQLite3('./data/article.sqlite3');
} catch(Exception $e) {
	echo 'DBとの接続に失敗';
	die($e -> getTraceAsString());
}

$input_id = sqlite_escape_string($_GET['p']);
// SQLiteに対する処理
$sql = "select rowid, * from article where rowid = '$input_id' or title = '$input_id';";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗: ' . $sqlerror);
}
$data_article = $result -> fetchArray();
// FTSテーブルからも情報取ってくる
$input_id = $data_article['id'];
$sql = "select * from fts_tag where rowid = '$input_id';";
$result = $db -> query($sql);
if (!$result) {
	die('読み込みに失敗🍣: ' . $sqlerror);
}
$data_fts = $result -> fetchArray();
if($data_article['title'] == null) {
	die("指定された記事がありません🍣");
}
echo $data_article['timestamp'];
echo '<h1 id="ar-title">', $data_article['title'], '</h1>';
echo '<div id="ar-headimage"><img src="./data/' . $data_article['headimage'] . '" /></div>';
echo '<div id="ar-body">'. $data_article['body'] .'</div>';
echo '<br />タグ: <div id="ar-tag">';
$tags = explode(" ", $data_fts['tag']);
for ($i = 0; $i < count($tags); $i++) {
	echo('<a href="?tag=' . $tags[$i] . '" class="ajaxtags">' . $tags[$i] . ' </a>');
}
echo '</div>';
echo '<button class="btn edit" href="./data/edit-article.html" value="'. $input_id .'">編集</button>';
echo '<a href="#myModal" role="button" class="btn btn-danger" data-toggle="modal">削除</a>';
echo ('
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">確認</h3>
	</div>
	<div class="modal-body">
		<p>
			本当に記事「'. $data_article['title'] .'」を削除しますか？
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">
			キャンセル
		</button>
		<button class="btn btn-danger del" data-dismiss="modal" href="' . $input_id . '">
			削除
		</button>
	</div>
</div>');
$db -> close();
?>
<!-- Modal -->