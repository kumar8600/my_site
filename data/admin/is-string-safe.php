<?php

function isStringId($str) {
	return preg_match('/^[0-9a-zA-Z_-]{3,20}$/', $str);
}

function isStringPassword($str) {
	return preg_match('/^[0-9a-zA-Z_-]{8,40}$/', $str);
}

function isStringEmail($str) {
	return preg_match('|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|', $str);
}

function isPostSafe($input) {
	if (!isStringId($input['userid'])) {
		echo $input['userid'];
		die("許可できないユーザIDです。正規表現 ^[0-9a-zA-Z_-]{3,20}$ にマッチさせてください");
	}
	if (!isStringPassword($input['password'])) {
		die("許可できないパスワードです。正規表現 ^[0-9a-zA-Z_-]{8,40}$ にマッチさせてください");
	}
	if (!isStringEmail($input['email'])) {
		die("許可できないメールアドレスです。正規表現 ^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$ にマッチさせてください");
	}
	return true;
}
?>