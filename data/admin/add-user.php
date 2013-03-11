<meta charset="UTF-8" />
<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
$input['name'] = $_POST['name'];
$input['email'] = $_POST['email'];

array_map("ifUnSetDie", $input);
$input['website'] = $_POST['website'];

$input['password'] = myCrypt($input['password']);

$db = connectAuthDB();

$sql = "INSERT INTO user (userid, password, name, email, website) VALUES('". 
$input['userid'] .
"', '". 
$input['password'] .
"', '". 
$input['name'] .
"', '". 
$input['email'] .
"', '". 
$input['website'] ."');";

queryDB($db, $sql);

$db -> close();
echo("ユーザーの追加に成功。");
?>