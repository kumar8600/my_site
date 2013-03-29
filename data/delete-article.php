<?php
require_once dirname(__FILE__) . '/connect-db.php';
require_once dirname(__FILE__) . '/admin/session.php';
require_once dirname(__FILE__) . '/delete-article-func.php';

ifUnSetDie($_POST['id']);
$input_id = $_POST['id'];

deleteArticle($input_id);

echo('記事の削除に成功。');
?>