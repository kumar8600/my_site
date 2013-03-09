<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$db = connectAuthDB();

$input['userid'] = $_POST['userid'];
ifUnSetDie($input['userid']);

$sql = "SELECT sysid, userid, name, email, website FROM user WHERE userid = '". $input['userid'] ."';";
$row = queryFetchArrayDB($db, $sql);


echo json_encode($row);

?>