<?php
require_once dirname(__FILE__) . '/admin/session.php';
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/aux-tag.php';

$db = connectDB();

$input['title'] = htmlspecialchars($_POST['title']);
$title = htmlspecialchars($_POST['title']);
$input['body'] = $_POST['body'];
$input['headimage'] = $_POST['headimage'];
$input['tag'] = htmlspecialchars($_POST['tag']);
$input['author'] = getSessionUser();
if (!$input['author']) {
	die("ログインしてください。");
}

array_map("ifUnSetDie", $input);
$input['rowid'] = $_POST['rowid'];

$input = array_map(array($db, 'escapeString'), $input);

// 同じタイトルを持つ記事がないかチェックする
if (empty($input['rowid'])) {
	$sql = "SELECT title FROM article WHERE title = '" . $input['title'] . "';";
} else {
	$sql = "SELECT title FROM article WHERE title = '" . $input['title'] . "' AND id <> " . $input['rowid'] . ";";
}

if (isExistDB($db, $sql))
	die("既に同じタイトルを持つ記事があります。違うタイトルに変更してください。");

// タグの文字列を整え、"タグ1¥nタグ2¥n" と言った並びにする
$input_tags = str_replace("　", " ", $input['tag']);
$input_tags = preg_replace("/(^ +| +$)/", "", $input_tags);
$input_tags = str_replace(" ", "\n", $input_tags);
$input_tags = "\n" . $input_tags . "\n";

// SQLiteに対する処理
if (empty($input['rowid'])) {
	$sql = "INSERT INTO article (title, body, headimage, tag, author) VALUES('" . $input['title'] . "', '" . $input['body'] . "', '" . $input['headimage'] . "', '" . $input_tags . "', '" . $input['author'] . "');";
} else {
	$sql = "SELECT author FROM article WHERE rowid = " . $input['rowid'] . ";";
	$row = queryFetchArrayDB($db, $sql);
	if ($row['author'] != $input['author']) {
		die("この記事を編集する権限を持っていません。");
	}
	$sql = "UPDATE article SET title = '" . $input['title'] . "', body = '" . $input['body'] . "', headimage = '" . $input['headimage'] . "', tag = '" . $input_tags . "' WHERE rowid = " . $input['rowid'] . ";";
}
$result = $db -> query($sql);

if (!$result) {
	die($input_rowid . '記事のインサートクエリーに失敗: ' . $sqlerror);
	//TODO:失敗時にSQLエラーを出力しないこと
}

$db -> close();

// 分かち書きした文をexplodeしてタグ補助用テーブルへ
$tags = explode(" ", $input['tag']);
for ($i = 0; $i < count($tags); $i++) {
	if ( !addAuxTag($tags[$i]) ) {
		die('タグ補助用情報の更新に失敗: ' . $sqlerror);
	}
}

if (empty($input['rowid'])) {
	echo('OK: 記事「' . $title . '」の作成に成功');
} else {
	echo('OK: 記事「' . $title . '」の編集に成功');
}
?>