<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$db = connectAuthDB();

$input['userid'] = $_POST['userid'];
$input['password'] = $_POST['password'];
$input['name'] = $_POST['name'];
$input['email'] = $_POST['email'];

array_map("ifUnSetDie", $input);
$input['website'] = $_POST['website'];

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
<meta charset="UTF-8" />
<form method="post" action="">
	ID<input type="text" name="userid" />
	パス<input type="password" name="password" />
	名前<input type="text" name="name" />
	メール<input type="text" name="email" />
	自分のサイト<input type="text" name="website" />
	<input type="text">送信</button>
</form>
