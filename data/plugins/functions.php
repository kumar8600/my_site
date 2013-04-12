<?php
function arrayToIni($arr, $path) {
	// 連想配列をiniファイルにして保存する関数。$pathへのディレクトリがなかったら再帰的に作成する。なので他の設定より先にこの関数を呼ぶと良い。
	if (!is_array($arr)) {
		return false;
	}
	if (is_readable($path) && is_file($path)) {
		$ini = parse_ini_file($path);
		$arr = array_merge($ini, $arr);
	}
	$dir = substr($path, 0, strrpos($path, '/'));
	if(!is_dir($dir)) {
		mkdir($dir, 0777, true);
	}
	foreach ($arr as $key => $value) {
		$outs[] = $key . ' = ' . $value . PHP_EOL;
	}
	$handle = fopen($path, "w");
	if ($handle == false) {
		return false;
	}
	foreach ($outs as $out) {
		if (fwrite($handle, $out) === false) {
			return false;
		}
	}
	fclose($handle);
	return true;
}

function getNameByFolder($folder) {
	$path = $GLOBALS['plugins_path'] . $folder . '/' . $GLOBALS['plugin_ini_name'];
	if (!is_file($path)) {
		return false;
	}
	$ini = parse_ini_file($path);
	return $ini['name'];
}

function getPluginIni($folder) {
	$path = $GLOBALS['plugins_path'] . $folder . '/' . $GLOBALS['plugin_ini_name'];
	if (!is_file($path)) {
		return false;
	}
	$ini = parse_ini_file($path);
	return $ini;
}

function getConfigNameById($folder, $id) {
	$path = getNavConfigDir() . $folder . '/' . $id . '-conf.ini';
	if (!is_file($path)) {
		return false;
	}
	$ini = parse_ini_file($path);
	return $ini['name'];
}

function getConfigIniById($folder, $id) {
	$path = getConfigDir() . $folder . '/' . $id . '-conf.ini';
	if (!is_file($path)) {
		return false;
	}
	$ini = parse_ini_file($path);
	return $ini;
}

function getMaxNumInFolder($dir, $preg) {
	// フォルダ内のファイルで$pregがマッチするもので、ファイルの頭に付いている整数を比較し、最大の整数を返す。
	if(!is_dir($dir)) {
		return false;
	}
	$dh = opendir($dir);
	if ($dh === false) {
		return false;
	}
	$max = 0;
	while (($file = readdir($dh)) !== false) {
		if (preg_match('/^[0-9]+/', $file, $matches) && preg_match($preg, $file)) {
			
			$num = intval($matches[0]);
			if ($num > $max) {
				$max = $num;
			}
		}
	}
	return $max;

}

function getConfigDir() {
	return $GLOBALS['plugins_config_path'];
}
?>