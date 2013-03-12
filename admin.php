<?php
require_once dirname(__FILE__) . '/data/admin/session.php';
$sesuserid = getSessionUser();
$get = $_GET['admin'];

if ($get == 'list-users') {
	require dirname(__FILE__) . '/data/admin/list-users.php';
} else if ($get == 'set-user') {
	require dirname(__FILE__) . '/data/admin/set-user-form.php';
} else if ($get == 'delete-user') {
	require dirname(__FILE__) . '/data/admin/delete-user-form.php';
}
	
?>