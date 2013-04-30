<?php
require_once dirname(__FILE__) . '/../functions.php';

function articlesExistInDay($month, $day, $year) {
	// 指定された日に記事があるか調べる関数。
	$timestamp = sprintf("%04d-%02d-%02d", $year, $month, $day);
	$db = connectDB();
	$sql = "SELECT COUNT(*) FROM article WHERE date(timestamp, 'localtime') = :ts";
	$stmt = $db -> prepare($sql);
	$stmt -> bindValue(":ts", $timestamp);
	$result = $stmt -> execute();
	$row = $result -> fetchArray();
	if($row[0]) {
		return true;
	}
	return false;
}

// タイムゾーンを設定。
date_default_timezone_set('Asia/Tokyo');
// 曜日の表現を定義
$dow_exp_ja = array('日', '月', '火', '水', '木', '金', '土');
$dow_exp_en = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$dow_exp = $dow_exp_ja;

// 今日の日付配列を得る
$today = getdate();

$year;
$month;
if (isset($_GET['year'])) {
	$year = $_GET['year'];
} else {
	$year = $today['year'];
}
if (isset($_GET['month'])) {
	$month = $_GET['month'];
} else {
	$month = $today['mon'];
}
// 月の日数を得る
$dim = cal_days_in_month(CAL_GREGORIAN, $month, $year);
// その月の1日の曜日を得る
$jd = cal_to_jd(CAL_GREGORIAN, $month, 1, $year);
$first_dow = jddayofweek($jd);
// 前の月を得る
if($month <= 1) {
	$back_y = $year - 1;
	$back_m = 12;
} else {
	$back_y = $year;
	$back_m = $month - 1;
}
// 次の月を得る
if($month >= 12) {
	$next_y = $year + 1;
	$next_m = 1;
} else {
	$next_y = $year;
	$next_m = $month + 1;
} 
// 以降表示
echo '<p class="text-center">';
echo '<strong>'. $year . '年' . $month . '月</strong>';
echo '<a class="nav-calender-ajax pull-left" href="./data/plugins/nav/'.basename(dirname(__FILE__)).'/data.php?year='.$back_y.'&month='.$back_m.'"><i class="icon-chevron-left"></i></a>';
echo '<a class="nav-calender-ajax pull-right" href="./data/plugins/nav/'.basename(dirname(__FILE__)).'/data.php?year='.$next_y.'&month='.$next_m.'"><i class="icon-chevron-right"></i></a>';
echo '</p>';
?>
<table style="width: 100%">
	<thead>
		<tr>
			<?php
			foreach ($dow_exp as $val) {
				echo '<th>' . $val . '</th>';
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		$count_all = 0;
		$count = 1;
		for ($i = 0; $count < $dim; $i++) {
			echo '<tr>';
			for ($j = 0; $j < 7; $j++) {
				if ($count <= $dim && $count_all >= $first_dow) {
					$exist = articlesExistInDay($month, $count, $year);
					if($exist) {
						echo '<th class="exist">';
					} else {
						echo '<th>';
					}
					if($exist)
						echo '<a class="ajaxtags" href="?date='.sprintf("%04d-%02d-%02d", $year, $month, $count).'">';
					echo $count;
					if($exist)
						echo '</a>';
					echo '</th>';
					$count++;
				} else {
					echo '<th></th>';
				}
				$count_all++;
			}
			echo '</tr>';
		}
		?>
	</tbody>
</table>