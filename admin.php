<?php
require_once dirname(__FILE__) . '/data/admin/session.php';
$sesuserid = getSessionUser();
$ses_sysid = getSessionSysId();
$get = $_GET['admin'];
if($get == "") {
	die("ログインしてください。");
}

if ($get == 'list-users') {
	require dirname(__FILE__) . '/data/admin/list-users.php';
} else if ($get == 'set-user') {
	require dirname(__FILE__) . '/data/admin/set-user-form.php';
} else if ($get == 'delete-user') {
	require dirname(__FILE__) . '/data/admin/delete-user-form.php';
} else if ($get == 'add-user') {
	require dirname(__FILE__) . '/data/admin/add-user-form.php';
}
	
?>