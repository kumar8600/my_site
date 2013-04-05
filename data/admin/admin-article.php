<?php
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/../connect-db.php';

if(isset($_POST['id'])) {
	$input_id = $_POST['id'];
} else {
	die("idがセットされていない");
}
$db = connectDB();
$input_id = $db -> escapeString($input_id);
$sql = "SELECT author, title FROM article WHERE id = '$input_id' OR title = '$input_id';";
try {
$row = queryFetchArrayDB($db, $sql);
} catch(Exception $ex) {
die();
}

if(isSysIdOrRoot($row['author']) == false) {
die("この記事を編集する権限を持っていません。" + $session_user);
}
?>
<a class="label label-info ajax" href="?admin=edit-article&p=<?php echo $input_id; ?>"> <i class="icon-edit icon-white"></i>編集 </a>
<a href="#delModal" role="button" class="label label-inverse" data-toggle="modal"><i class="icon-trash icon-white"></i>削除</a>
<div id="delModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">確認</h3>
	</div>
	<div class="modal-body">
		<p>
			本当に記事「<?php echo $row['title']; ?>」を削除しますか？
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">
			キャンセル
		</button>
		<a class="btn btn-danger del" data-dismiss="modal" href="./data/delete-article.php?id=<?php echo $input_id; ?>"> 削除 </a>
	</div>
</div>