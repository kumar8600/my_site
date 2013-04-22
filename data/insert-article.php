<?php
require_once dirname(__FILE__) . '/admin/session.php';
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/aux-tag.php';
require_once dirname(__FILE__) . '/make-preface.php';

$input['title'] = htmlspecialchars($_POST['title']);
$title = htmlspecialchars($_POST['title']);
$input['headimage'] = $_POST['headimage'];
$input['tag'] = htmlspecialchars($_POST['tag']);
$input['author'] = getSessionSysId();
if ($input['author'] == "") {
	die("ログインしてください。");
}

array_map("ifUnSetDie", $input);
$input['rowid'] = $_POST['rowid'];
array_map("strip_tags", $input);
$input['body'] = $_POST['body'];

$input_preface = makePreface($input['body']);
$input_body = antiXSS($input['body']); 

$db = connectDB();
// 同じタイトルを持つ記事がないかチェックする
if (empty($input['rowid'])) {
	$sql = "SELECT COUNT(*) FROM article WHERE title = :title";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":title", $input['title']);
} else {
	$sql = "SELECT COUNT(*) FROM article WHERE title = :title AND id <> :id;";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":title", $input['title']);
	$stmt -> bindValue(":id", $input['rowid']);
}
$result = $stmt -> execute();
$row = $result -> fetchArray();
$num = $row['COUNT(*)'];

if ($num > 0)
	die("既に同じタイトルを持つ記事があります。違うタイトルに変更してください。");

// タグの文字列を整え、"\nタグ1\nタグ2\n" と言った並びにする
$input['tag'] = str_replace("　", " ", $input['tag']);
$input['tag'] = preg_replace("/(^ +| +$)/", "", $input['tag']);
$tags = explode(" ", $input['tag']);
$tags = array_unique($tags); // 重複するのは消す
$input_tags = implode(" ", $tags);
$input_tags = str_replace(" ", "\n", $input_tags);
$input_tags = "\n" . $input_tags . "\n";

// SQLiteに対する処理
if (empty($input['rowid'])) {
	$sql = "INSERT INTO article (title, body, preface, headimage, tag, author) VALUES(:title, :body, :preface, :headimage, :tag, :author);";
	$stmt = $db -> prepare($sql);
} else {
	// 記事書き換えの場合、著者が同じか調べる
	$sql = "SELECT author, tag FROM article WHERE rowid = :id";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":id", $input['rowid']);
	$result = $stmt -> execute();
	$row = $result -> fetchArray();
	if (!isSysIdOrRoot($row['author'])) {
		die("この記事を編集する権限を持っていません。");
	}
	
	$old_tags = $row['tag'];
	$sql = "UPDATE article SET title = :title, body = :body, preface = :preface, headimage = :headimage, tag = :tag WHERE rowid = :id;";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":id", $input['rowid']);
}
$stmt -> bindValue(":title", $input['title']);
// XSS対策済みのbodyを準備する。
$stmt -> bindValue(":body", $input_body);
$stmt -> bindValue(":headimage", $input['headimage']);
$stmt -> bindValue(":tag", $input_tags);
$stmt -> bindValue(":author", $input['author']);
// 序文を作る
$stmt -> bindValue(":preface", $input_preface);

$result = $stmt -> execute();

if (!$result) {
	die($input_rowid . '記事のインサートクエリーに失敗: ' . $sqlerror);
	//TODO:失敗時にSQLエラーを出力しないこと
}
if (empty($input['rowid'])) {
	$insert_rowid = $db -> lastInsertRowID();
}

$db -> close();


// 分かち書きした文をexplodeしてタグ補助用テーブルへ
//$tags = explode(" ", $input['tag']);
if (empty($input['rowid'])) {
	updateAuxTags($insert_rowid, $tags);
} else {
	$old_tags_arr = preg_split("/\s+/", $old_tags, -1, PREG_SPLIT_NO_EMPTY);
	updateAuxTags($input['rowid'], $tags, $old_tags_arr);
}

if (empty($input['rowid'])) {
	echo('OK: 記事「' . $title . '」の作成に成功');
} else {
	echo('OK: 記事「' . $title . '」の編集に成功');
}
?>