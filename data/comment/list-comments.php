<legend>コメント</legend>
<?php
require_once dirname(__FILE__) . '/comment-func.php';

if(isset($_GET['p'])) {
	$input_p = $_GET['p'];
} else {
	die("アドレスに間違いが");
}

if(isset($_GET['offset'])) {
	$input_start = $_GET['offset'];
} else {
	$input_start = 0;
}
	
if(isset($_GET['limit'])) {
	$input_count = $_GET['limit'];
} else {
	$input_count = 1000;
}


listComments($input_p, $input_start, $input_count);

?>