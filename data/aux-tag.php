<?php
require_once dirname(__FILE__) . '/connect-db.php';

function addAuxTag($name) {
	if (!isset($name)) {
		return false;
	}

	$db = connectDB();

	$sql = "SELECT frequency FROM aux_tag WHERE name = '$name';";
	$result = queryDB($db, $sql);
	$row = $result -> fetchArray(SQLITE3_ASSOC);

	if ($row) {
		$freq = $row['frequency'] + 1;
		$sql = "UPDATE aux_tag SET frequency = $freq WHERE name = '$name';";
	} else {
		$sql = "INSERT INTO aux_tag VALUES('$name', 1)";
	}

	queryDB($db, $sql);

	$db -> close();
	return true;
}

function removeAuxTag($name) {
	if (!isset($name)) {
		return false;
	}

	$db = connectDB();

	$sql = "SELECT frequency FROM aux_tag WHERE name = '$name';";
	$result = queryDB($db, $sql);
	$row = $result -> fetchArray(SQLITE3_ASSOC);
	if (!$row)
		return false;

	if ($row['frequency'] > 1) {
		$freq = $row['frequency'] - 1;
		$sql = "UPDATE aux_tag SET frequency = $freq WHERE name = '$name';";
	} else {
		$sql = "DELETE FROM aux_tag WHERE name = '$name';";
	}
	queryDB($db, $sql);

	$db -> close();
	return true;
}

function updateAuxTags($tags, $old_tags = null) {
	//$tags, $old_tagsより、増やすタグ配列と減らすタグ配列を作る。
	$ic = count($tags);
	$jc = count($old_tags);
	for ($i = 0; $i < $ic; $i++) {
		for ($j = 0; $j < $jc; $j++) {
			if ($tags[$i] === $old_tags[$j]) {
				$tags[$i] = null;
				$old_tags[$j] = null;
				break;
			}
		}
	}

	for ($i = 0; $i < count($tags); $i++) {
		if ($tags[$i] != null) {
			if (!addAuxTag($tags[$i])) {
				die('タグ補助用情報の更新に失敗: ' . $sqlerror);
			}
		}
	}
	for ($i = 0; $i < count($old_tags); $i++) {
		if ($old_tags[$i] != null) {
			if (!removeAuxTag($old_tags[$i])) {
				die('タグ補助用情報の更新に失敗: ' . $sqlerror);
			}
		}
	}
}
?>