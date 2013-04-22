<?php
require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/session.php';

$_POST = array_map("strip_tags", $_POST);
$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
array_map("ifUnSetDie", $input);

try {
	if (authorize($input['userid'], $input['password'])) {
		//ユーザIDとシステムIDをセッション変数へ代入
		setSessionUser($input['userid']);
		
		echo true;
	} else {
		echo false;
	}
} catch(Exception $ex) {
	//echo $ex;
}
?>
