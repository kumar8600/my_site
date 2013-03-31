<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/comment-func.php';

$input['p'] = $_POST['p'];
$input['name'] = htmlspecialchars($_POST['name']);
$input['email'] = $_POST['email'];
$input['body'] = htmlspecialchars($_POST['body']);
array_map("ifUnSetDie", $input);

$input['ip'] = $_SERVER['REMOTE_ADDR'];

if(!sendComments($input['p'], $input['name'], $input['email'], $input['ip'], $input['body'])) {
	die("コメントの投稿に失敗");
}
echo "OK: コメントを投稿した";
?>