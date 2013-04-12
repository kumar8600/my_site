<?php
function writeText($str, $path) {
	// テキストファイルを出力する。フォルダがないときは再帰的に作る。
	$dir = substr($path, 0, strrpos($path, '/'));
	if(!is_dir($dir)) {
		mkdir($dir, 0777, true);
	}
	$handle = fopen($path, "w");
	if ($handle == false) {
		return false;
	}
	if (fwrite($handle, $str) === false) {
		return false;
	}
	fclose($handle);
	return true;
}
function readText($path) {
	// テキストファイルを入力する。
	return file_get_contents($path);
}
function getConfigDir() {
	return $GLOBALS['plugins_config_path'];
}
?>