<?php
require_once dirname(__FILE__) . '/data/admin/session.php';
$sesuserid = getSessionUser();
$ses_sysid = getSessionSysId();
$_GET = array_map("strip_tags", $_GET);
$get = $_GET['admin'];
if($get == "") {
	die('<meta charset="UTF-8" />URLがおかしいです');
}

if ($get == 'list-users') {
	require dirname(__FILE__) . '/data/admin/list-users.php';
} else if ($get == 'set-user') {
	require dirname(__FILE__) . '/data/admin/set-user-form.php';
} else if ($get == 'delete-user') {
	require dirname(__FILE__) . '/data/admin/delete-user-form.php';
} else if ($get == 'add-user') {
	require dirname(__FILE__) . '/data/admin/add-user-form.php';
} else if ($get == 'edit-article') {
	require dirname(__FILE__) . '/data/edit-article.php';
} else if ($get == 'set-site') {
	require dirname(__FILE__) . '/data/admin/set-site-form.php';
} else if ($get == 'set-nav') {
	require dirname(__FILE__) . '/data/nav/update-form.php';
}
	
?>