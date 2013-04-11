<?php
require_once dirname(__FILE__) . '/create-table-func.php';

if (createTableArticle()) {
	//echo("記事用テーブルの作成に成功。");
} else {
	die("記事用テーブルの作成に失敗。");
}
if (createTableMapTag()) {
	//echo("タグ用テーブルの作成に成功。");
} else {
	die("タグ用テーブルの作成に失敗。");
}
if (createTableAuxTag()) {
	//echo("タグ補助用テーブルの作成に成功。");
} else {
	die("タグ補助用テーブルの作成に失敗。");
}
if (createTableSettings()) {
	//echo("サイト設定用テーブルの作成に成功。");
} else {
	die("サイト設定用テーブルの作成に失敗。");
}
if (createTableNav()) {
	//echo("ナビゲーションカラム用テーブルの作成に成功。");
} else {
	die("ナビゲーションカラム用テーブルの作成に失敗。");
}
if (createTableAuth()) {
	//echo("ユーザー情報用テーブルの作成に成功。");
} else {
	die("ユーザー情報用テーブルの作成に失敗。");
}
if (createTableComment()) {
	//echo("コメント用テーブルの作成に成功。");
} else {
	die("コメント用テーブルの作成に失敗。");
}
?>