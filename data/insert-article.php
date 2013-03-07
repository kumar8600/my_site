<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

$input['title'] = $_POST['title'];
$input['body'] = $_POST['body'];
$input['headimage'] = $_POST['headimage'];
$input['tag'] = $_POST['tag'];

function ifUnSetDie($val) {
	if(!isset($val)) {
		die("セットされていない値がある");
	}
}
array_map("ifUnSetDie", $input);
$input['rowid'] = $_POST['rowid'];
array_map(array($db, 'escapeString'), $input);

// SQLiteに対する処理
if (empty($input['rowid'])) {
	$sql = "INSERT INTO article (title, body, headimage) VALUES('".$input['title']."', '".$input['body']."', '".$input['headimage']."');";
} else {
	$sql = "UPDATE article SET title = '".$input['title']."', body = '".$input['body']."', headimage = '".$input['headimage']."' WHERE rowid = ".$input['rowid'].";";
}
$result = $db -> query($sql);

if (!$result) {
	die($input_rowid . '記事のインサートクエリーに失敗: ' . $sqlerror);
	//TODO:失敗時にSQLエラーを出力しないこと
}

// 分かち書きした文をタグ検索用テーブルへ
if (empty($input['rowid'])) {
	$id_inserted = $db -> lastInsertRowId();
	$sql = "INSERT INTO fts_tag (docid, tag) VALUES($id_inserted, '".$input['tag']."');";
} else {
	$id_inserted = $input['rowid'];
	$sql = "UPDATE fts_tag SET tag = '".$input['tag']."' WHERE fts_tag.docid = $id_inserted;";
}
$result = $db -> query($sql);

if (!$result) {
	die('タグ検索用情報のインサートクエリーに失敗: ' . $sqlerror);
}

$db -> close();

if (empty($input['rowid']))  {
	echo('記事「' . $input['title'] . '」の作成に成功');
} else {
	echo('記事「' . $input['title'] . '」の編集に成功');
}
?>