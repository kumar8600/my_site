<legend>コメント</legend>
<?php
require_once dirname(__FILE__) . '/comment-func.php';

$input_p = $_GET['p'];
if($input_p == "") {
	die("アドレスに間違いが");
}
$input_start = $_GET['offset'];
$input_count = $_GET['limit'];
if($input_start == "") {
	$input_start = 0;
}
if($input_count == "") {
	$input_count = 1000;
}


listComments($input_p, $input_start, $input_count);

?>