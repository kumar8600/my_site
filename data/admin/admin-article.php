<?php
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/../connect-db.php';

$input_id = $_POST['id'];
if($input_id == "") {
die("idがセットされていない");
}
$db = connectDB();
$input_id = $db -> escapeString($input_id);
$sql = "SELECT author FROM article WHERE id = '$input_id' OR title = '$input_id';";
try {
$row = queryFetchArrayDB($db, $sql);
} catch(Exception $ex) {
die();
}

if(isSysIdOrRoot($row['author']) == false) {
	die("この記事を編集する権限を持っていません。" + $session_user);
}
?>
<button class="btn edit" href="./data/edit-article.html" value="">
	<i class="icon-edit"></i>編集
</button>
<a href="#delModal" role="button" class="btn btn-danger" data-toggle="modal"><i class="icon-trash icon-white"></i>削除</a>
<div id="delModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			×
		</button>
		<h3 id="myModalLabel">確認</h3>
	</div>
	<div class="modal-body">
		<p>
			本当にこの記事を削除しますか？
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">
			キャンセル
		</button>
		<button class="btn btn-danger del" data-dismiss="modal" value="">
			削除
		</button>
	</div>
</div>