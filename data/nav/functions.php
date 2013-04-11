<?php
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/../../config.php';

function showNav() {
	$db1 = connectSettingsDB();

	$sql = "SELECT * FROM nav ORDER BY id;";
	$result1 = $db1 -> query($sql);

	$i = 0;
	while ($row1 = $result1 -> fetchArray()) {
		echo "<div>";

		if (isset($row1['configid'])) {
			$_GET['configid'] = $row1['configid'];
		}
		require ($GLOBALS['plugins_nav_path'] . $row1['folder'] . '/' . $row1['page']);
		echo "</div>";
	}
	$db1 -> close();
}

function updateNavOrder($order = null) {
	// $order[][]は ['folder']と['name']と['configid']を持つ
	$db = connectSettingsDB();

	$sql = "DELETE FROM nav;";
	$result = $db -> query($sql);
	if ($result == false) {
		die("今までのナビゲーション設定の削除に失敗。");
	}
	if($order == null) {
		return true;
	}

	$stmt = $db -> prepare("INSERT INTO nav (folder, page, configid) VALUES (:folder, :page, :configid)");
	$stmt -> bindParam(":folder", $folder);
	$stmt -> bindParam(":page", $page);
	$stmt -> bindParam(":configid", $configid);

	foreach ($order as $arr) {
		if (is_array($arr)) {
			$folder = $arr['folder'];
			$page = $arr['page'];
			$configid = $arr['configid'];
			$stmt -> execute();
		}
	}
	return true;
}

function parseConfigIni($ini_file) {
	if (!file_exists($ini_file)) {
		return false;
	}
	$ini = parse_ini_file($ini_file);
	if ($ini['name'] == "") {
		return false;
	}
	$ini['c_name'] = $ini['name'];
	$ini['c_desc'] = $ini['desc'];
	unset($ini['name'], $ini['desc']);
	return $ini;
}

function getConfigList($dir) {
	$dir = $dir . '/';
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir . $file) == 'file' && preg_match('/^[0-9]+-conf.ini$/', $file)) {
					$ini = parseConfigIni($dir . $file);
					if ($ini) {
						$ini['configid'] = preg_replace('/-conf.ini$/', '', $file);
						$ret[] = $ini;
					}
				}
			}
			closedir($dh);
			return $ret;
		}
	}
}

function parsePluginIni($dir) {
	$ini_file = $dir . '/' . $GLOBALS['plugin_ini_name'];
	if (!file_exists($ini_file)) {
		return false;
	}
	$ini = parse_ini_file($ini_file);
	if ($ini['page'] == "" || $ini['name'] == "") {
		return false;
	}
	return $ini;
}

function getNavPluginsList() {
	$dir = $GLOBALS['plugins_nav_path'];
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($dir . $file) == 'dir' && $file != "." && $file != "..") {
					$ini = parsePluginIni($dir . $file);
					if ($ini) {
						$ini['folder'] = $file;
						$ret[] = $ini;
						// config値があるならconfigフォルダを調べる。
						if (isset($ini['config'])) {
							$confs = getConfigList(getNavConfigDir() . $file);
							if (is_array($confs)) {
								foreach ($confs as $conf) {
									$ini_c = array_merge($ini, $conf);
									$ret[] = $ini_c;
								}
							}
						}

					}
				}
			}
			closedir($dh);
			return $ret;
		}
	}
	return false;
}

function deleteConfig($folder, $id) {
	$db = connectSettingsDB();
	$stmt = $db -> prepare("DELETE FROM nav WHERE folder = :folder AND configid = :configid");
	$stmt -> bindValue(":folder", $folder);
	$stmt -> bindValue(":configid", $id);
	$stmt -> execute();
	$db -> close();
	
	$path = getNavConfigDir() . $folder . '/' . $id . '-conf.ini';
	$ret = unlink($path);
	return $ret;
}

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
	$path = $GLOBALS['plugins_nav_path'] . $folder . '/' . $GLOBALS['plugin_ini_name'];
	if (!is_file($path)) {
		return false;
	}
	$ini = parse_ini_file($path);
	return $ini['name'];
}

function getPluginIni($folder) {
	$path = $GLOBALS['plugins_nav_path'] . $folder . '/' . $GLOBALS['plugin_ini_name'];
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
	$path = getNavConfigDir() . $folder . '/' . $id . '-conf.ini';
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

function getNavConfigDir() {
	return $GLOBALS['plugins_config_path'] . 'nav/';
}
?>