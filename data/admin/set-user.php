<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/is-string-safe.php';

$input['olduserid'] = $_POST['olduserid'];
$input['userid'] = $_POST['userid'];

$input['name'] = $_POST['name'];
$input['email'] = $_POST['email'];

//フォームには予め値を入力済み。パスワード変更しない場合はパスワード入力必要なし
array_map("ifUnSetDie", $input);
$input['oldpassword'] = $_POST['oldpassword'];
$input['password'] = $_POST['password'];
$input['password_re'] = $_POST['password_re'];
$input['website'] = $_POST['website'];
$input['introduction'] = $_POST['introduction'];

if(!isStringEmail($input['email'])) {
	die("許可できないメールアドレスです。");
}


//パスワードが２回同じ物が入力されてるか確認
if ($input['password'] != $input['password_re']) {
	die("パスワードは２回同じ物を入力してください。");
}

//パスワード変更する場合とID変更する場合は古いパスワードが必要
if ($input['password'] != "" || $input['userid'] != $input['olduserid']) {
	isPostSafe($input);
	try {
		authorize($input['olduserid'], $input['oldpassword']);
	} catch(Exception $ex) {
		die("パスワードに間違いがあります。");
	}

	$input['oldpassword'] = myCrypt($input['oldpassword']);
	if ($input['password'] == "") {
		$input['password'] = $input['oldpassword'];
	} else {
		$input['password'] = myCrypt($input['password']);
	}
}
$db = connectAuthDB();

// 同じIDを持つアカウントがないかチェックする
$sql = "SELECT userid FROM user WHERE userid = '" . $input['userid'] . "' AND userid != '" . $input['olduserid'] . "';";
if (isExistDB($db, $sql))
	die("既に同じユーザーIDを持つアカウントがあります。違うIDに変更してください");

if ($input['password'] != "") {
	$sql = "UPDATE user SET userid = :userid, password = :password, name = :name, email = :email, website = :website, introduction = :intro WHERE userid = :olduserid AND password = :oldpassword";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":password", $input['password']);
	$stmt -> bindValue(":oldpassword", $input['oldpassword']);
} else {
	$sql = "UPDATE user SET userid = :userid, name = :name, email = :email, website = :website, introduction = :intro WHERE userid = :olduserid";
	$stmt = $db -> prepare($sql);
}
$stmt -> bindValue(":olduserid", $input['olduserid']);
$stmt -> bindValue(":userid", $input['userid']);
$stmt -> bindValue(":name", $input['name']);
$stmt -> bindValue(":email", $input['email']);
$stmt -> bindValue(":website", $input['website']);
$stmt -> bindValue(":intro", $input['introduction']);

$stmt -> execute();

$db -> close();
//セッション情報を更新する
setSession("userid", $input['userid']);
echo("OK: ユーザーの設定変更に成功。");
?>