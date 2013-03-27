<?php
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/../connect-db.php';

$input['userid'] = getSessionUser();
$dba = connectAuthDB();
$sql = "SELECT name FROM user WHERE userid = '".$input['userid']."';";
$author = queryFetchArrayDB($dba, $sql);
$dba -> close();
$input['name'] = $author['name'];

echo json_encode($input);

?>