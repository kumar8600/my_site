<?php
require_once dirname(__FILE__) . '/../connect-db.php';

function getRanking($limit = 100, $offset = 0, $period = 1, $where_timestamp = "") {
	// TODO: $limit位までのランキングを取得。連想配列として返す。
	$db = connectLogsDB();

	if ($where_timestamp != "") {
		$where_timestamp = " AND " . $where_timestamp;
	}
	$sql = "SELECT * FROM rank WHERE auxid = (SELECT id FROM aux_rank WHERE period = :period" . $where_timestamp . " LIMIT 1) ORDER BY ranking DESC OFFSET :offset LIMIT :limit";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":limit", $limit);
	$stmt -> bindValue(":offset", $offset);
	$stmt -> bindValue(":period", $period);
	$stmt -> bindValue(":timestamp", $timestamp);

	$result = $stmt -> execute();
	$rows;
	while ($row = $result -> fetchArray()) {
		$rows[] = $row;
	}

	$db -> close();

	return $rows;
}

function getDailyRanking($limit = 100, $offset = 0, $date = "timestamp = (SELECT MAX(timestamp) FROM aux_rank WHERE period = :period)") {
	return getRanking($limit, $offset, 1, $date);
}

function getWeeklyRanking($limit = 100, $offset = 0, $date = "timestamp = (SELECT MAX(timestamp) FROM aux_rank WHERE period = :period)") {
	return getRanking($limit, $offset, 7, $date);
}

function getMonthlyRanking($limit = 100, $offset = 0, $date = "timestamp = (SELECT MAX(timestamp) FROM aux_rank WHERE period = :period)") {
	return getRanking($limit, $offset, 31, $date);
}

function calcRanking($limit = 100) {
	// TODO: アクセスログからランキングを$limit位まで作成
	$db = connectLogsDB();

	calcTotalRanking($db, $limit);
	calcDailyRanking($db, $limit);
	calcWeeklyRanking($db, $limit);
	calcMonthlyRanking($db, $limit);

	$db -> close();
}

function isRowExist($db, $table, $where = "") {
	$sql = "SELECT COUNT(*) FROM " . $table;
	if ($where != "") {
		$sql .= " WHERE " . $where;
	}
	$stmt = $db -> prepare($sql);
	$result = $stmt -> execute();
	$row = $result -> fetchArray();
	if ($row[0]) {
		return true;
	}
	return false;
}

function insertAuxRank($db, $period, $timestamp) {
	// TODO: 新たなaux_rankを挿入
	$sql = "INSERT INTO aux_rank (period, timestamp) VALUES(:period, :timestamp)";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":period", $period);
	$stmt -> bindValue(":timestamp", $timestamp);
	$result = $stmt -> execute();
	if (!$result) {
		return false;
	}
	$auxid = $db -> lastInsertRowID();
	return $auxid;
}

function selectLogCount($db, $where = "", $limit = 100) {
	// TODO: 閲覧数を多い順に数える
	if ($where != "") {
		$where = " WHERE " . $where;
	}
	$sql = "SELECT COUNT(*) AS freq, articleid FROM log" . $where . " GROUP BY articleid ORDER BY freq DESC LIMIT " . $limit;
	$stmt = $db -> prepare($sql);
	$result = $stmt -> execute();

	return $result;
}

function insertRank($db, $result, $auxid) {
	// TODO: 閲覧数を元にランキング作成
	$sql2 = "INSERT INTO rank (articleid, ranking, freq, auxid) VALUES(:articleid, :ranking, :freq, :auxid)";
	$stmt2 = $db -> prepare($sql2);
	$stmt2 -> bindParam(":articleid", $articleid);
	$stmt2 -> bindParam(":ranking", $rank);
	$stmt2 -> bindParam(":freq", $freq);
	$stmt2 -> bindParam(":auxid", $auxid);
	$i = 0;
	while ($row = $result -> fetchArray()) {
		$i++;
		$articleid = $row['articleid'];
		$rank = $i;
		$freq = $row['freq'];

		$result = $stmt2 -> execute();
	}
	// 書き込んだ回数を返す
	return $i;
}

function absCalcRanking($db, $period, $timestamp, $where = "", $limit = 100) {
	// TODO: ランキングの算出を抽象化した関数
	/*
	 * // $whereの条件に当てはまる列があるなら、なにもしないで終了
	 $period_where = "period = ". $period;
	 if ($where != "") {
	 $period_where .= " AND ". $where;
	 }
	 if (isRowExist($db, "aux_rank", $period_where)) {
	 return true;
	 }
	 */

	// 閲覧数を多い順に数える
	$result = selectLogCount($db, $where, $limit);
	if (!$result) {
		return false;
	}

	// 新たなaux_rankを挿入
	$auxid = insertAuxRank($db, $period, $timestamp);
	if ($auxid === false) {
		return false;
	}

	// ランキングテーブルに挿入
	insertRank($db, $result, $auxid);

	return true;
}

function calcTotalRanking($db, $limit) {
	// TODO: 総合ランキングを算出
	$where = "";
	$period = 0;
	$timestamp = "datetime('now')";
	$flag = absCalcRanking($db, $period, $timestamp, $where, $limit);

	return $flag;
}

function calcNDaysRanking($db, $limit, $n) {
	// TODO: N日分のランキングを毎日算出
	$where = "datetime(timestamp) > datetime('now', '-" . $n . " days')";
	$period = $n;
	$timestamp = "datetime('now')";
	$flag = absCalcRanking($db, $period, $timestamp, $where, $limit);

	return $flag;
}

function calcDailyRanking($db, $limit) {
	// TODO: 1日分のランキングを算出
	$flag = calcNDaysRanking($db, $limit, 1);

	return $flag;
}

function calcWeeklyRanking($db, $limit) {
	// TODO: 7日分のランキングを算出
	$flag = calcNDaysRanking($db, $limit, 7);

	return $flag;
}

function calcMonthlyRanking($db, $limit) {
	// TODO: 31日分のランキングを算出
	$flag = calcNDaysRanking($db, $limit, 31);

	return $flag;
}

function logAccess($articleid) {
	// TODO: 記事へのアクセスを記録する。
	$db = connectLogsDB();
	$sql = "INSERT INTO log (articleid) VALUES (:arid)";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":arid", $articleid);
	$result = $stmt -> execute();
	if ($result)
		return true;
	else
		return false;
}
