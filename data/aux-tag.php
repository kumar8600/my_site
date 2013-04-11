<?php
require_once dirname(__FILE__) . '/connect-db.php';

function addTag($articleid, $name) {
	if (!isset($name) || !isset($articleid)) {
		return false;
	}

	$db = connectDB();

	$sql = "INSERT INTO map_tag(tagid, articleid) VALUES((SELECT id FROM aux_tag WHERE name = :name), :articleid)";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name);
	$stmt -> bindValue(':articleid', $articleid);
	$result = $stmt -> execute();
	if ($result === false) {
		return false;
	}
	$db -> close();
	return true;
}

function removeTag($articleid, $name) {
	if (!isset($name) || !isset($articleid)) {
		return false;
	}

	$db = connectDB();

	$sql = "DELETE FROM map_tag WHERE tagid = (SELECT id FROM aux_tag WHERE name = :name) AND articleid = :articleid";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name);
	$stmt -> bindValue(':articleid', $articleid);
	$result = $stmt -> execute();
	if ($result === false) {
		return false;
	}
	$db -> close();
	return true;
}

function addAuxTag($name) {
	if (!isset($name)) {
		return false;
	}

	$db = connectDB();

	$sql = "SELECT frequency FROM aux_tag WHERE name = :name";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name);
	$result = $stmt -> execute();
	$row = $result -> fetchArray(SQLITE3_ASSOC);

	if ($row) {
		$freq = $row['frequency'] + 1;
		$sql = "UPDATE aux_tag SET frequency = :freq WHERE name = :name";
	} else {
		$sql = "INSERT INTO aux_tag (name, frequency) VALUES(:name, 1)";
	}
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name);
	if (isset($freq))
		$stmt -> bindValue(':freq', $freq);
	$result = $stmt -> execute();

	$db -> close();
	return true;
}

function removeAuxTag($name) {
	if (!isset($name)) {
		return false;
	}

	$db = connectDB();

	$sql = "SELECT frequency FROM aux_tag WHERE name = :name";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name);
	$result = $stmt -> execute();
	$row = $result -> fetchArray(SQLITE3_ASSOC);
	if (!$row)
		return false;

	if ($row['frequency'] > 1) {
		$freq = $row['frequency'] - 1;
		$sql = "UPDATE aux_tag SET frequency :freq WHERE name = :name";
	} else {
		$sql = "DELETE FROM aux_tag WHERE name = :name;";
	}
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(':name', $name);
	if (isset($freq))
		$stmt -> bindValue(':freq', $freq);
	$result = $stmt -> execute();

	$db -> close();
	return true;
}

function updateAuxTags($articleid, $tags, $old_tags = null) {
	//$tags, $old_tagsより、増やすタグ配列と減らすタグ配列を作る。
	if (is_array($old_tags) && is_array($tags)) {
		foreach ($tags as $key => $value) {
			foreach ($old_tags as $o_key => $o_value) {
				if ($value === $o_value) {
					$tags[$key] = null;
					$old_tags[$o_key] = null;
				}
			}
		}
	}
	if (is_array($tags)) {
		foreach ($tags as $value) {
			if ($value != null) {
				if (!addAuxTag($value)) {
					die('タグ補助用情報の更新に失敗: ' . $sqlerror);
				}
				if (!addTag($articleid, $value)) {
					die('タグマップ情報の更新に失敗(追加)');
				}
			}
		}
	}

	if (is_array($old_tags)) {
		foreach ($old_tags as $value) {
			if ($value != null) {
				if (!removeAuxTag($value)) {
					die('タグ補助用情報の更新に失敗: ' . $sqlerror);
				}
				if (!removeTag($articleid, $value)) {
					die('タグマップ情報の更新に失敗(削除)');
				}
			}
		}
	}
}
?>