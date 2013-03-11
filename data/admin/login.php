<?php
require_once dirname(__FILE__) . '/auth.php';

$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
array_map("ifUnSetDie", $input);

if(authorize($input['userid'], $input['password'])) {
	echo true;
} else {
	echo false;
}
?>