<style>
	.plugins-container {
		width: 50%;
		float: left;
	}
	.plugins-container h5 {
		text-align: center;
	}
	.plugins-container ul {
		list-style-type: none;
		margin: 0;
		padding: 10px;
		background-color: #D9D9D9;
		min-height: 40px;
	}
	.plugins-container li {
		margin: 0 3px 3px 3px;
		padding: 0.4em;
		font-size: 1.2em;
		overflow: hidden;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		-o-border-radius: 5px;
		-ms-border-radius: 5px;
		border-radius: 5px;
	}
	ul.sortable-notuse {
		background-color: #FFCCBA;
	}
	.sortable-notuse li {
		max-height: 20px;
	}
	.color-red {
		background-color: #DD514C;
		color: #FFFFFF;
		border: solid 2px #D59392;
	}
	.color-blue {
		background-color: #139FF7;
		color: #FFFFFF;
		border: solid 2px #1392E9;
	}
	#container {
		display: block;
		width: 100%;
		float: left;
		margin-bottom: 10px;
	}
	.cantdrag {
		background-color: #0782C1;!important;
		box-shadow: 0 10px 0 #bfbfbf;
	}
	.configured-blue {
		border-top: none !important;
		background-color: #189DE1;!important;
		-moz-border-radius: 0 0 5px 5px !important;
		-webkit-border-radius: 0 0 5px 5px !important;
		-o-border-radius: 0 0 5px 5px !important;
		-ms-border-radius: 0 0 5px 5px !important;
		border-radius: 0 0 5px 5px !important;
	}
	.plugins-container a {
		color: #FED22F;
	}
</style>
<?php
require_once dirname(__FILE__) . '/functions.php';
require_once dirname(__FILE__) . '/../connect-db.php';
require_once dirname(__FILE__) . '/../admin/session.php';

if (!isRootUser()) {
	die("rootユーザーとしてログインしてください。");
}
$plugins = getNavPluginsList();
$db = connectSettingsDB();

$sql = "SELECT * FROM nav ORDER BY id;";
$result = $db -> query($sql);

if ($result) {
	while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
		$row = array_merge($row, getPluginIni($row['folder']));
		if($row['configid'] != "") {
			$ini = getConfigIniById($row['folder'], $row['configid']);
			$row['c_name'] = $ini['name'];
			$row['c_desc'] = $ini['desc'];
		}
		$using[] = $row;
	}
}

$db -> close();

function showPluginInfo($value) {
	if ($value['config']) {
		echo '<a class="pull-right ajax" href="?ajax=./data/plugins/nav/' . $value['folder'] . '/' . $value['config'];
		if ($value['configid'] != "") {
			echo '?configid=' . $value['configid'];
			echo '"><i class="icon-wrench"></i>設定</a>';
			echo '<a href="?ajax=./data/nav/delete-conf-form.php?folder=' . $value['folder'] . '&configid=' . $value['configid'] . '" class="pull-right ajax"><i class="icon-trash"></i></a>';
		} else {
			echo '"><i class="icon-wrench"></i>新規設定</a>';
		}
	}

	if ($value['configid'] == "") {
		echo '<span style="display:block;">' . $value['name'];
	}
	if ($value['configid'] != "") {
		echo '<span style="display:block;">' . $value['c_name'];
	}

	echo '</span>';
	if ($value['configid'] == "")
		echo '<small>' . $value['desc'] . '</small>';
	if ($value['configid'] != "") {
		echo '<small>' . $value['c_desc'] . '</small>';
	}
}

?>
<legend class="p-title">
	ナビゲーションカラムの設定
</legend>
<p>
	使用するプラグインを並べてください。並べた順番どおりに表示されます。未設定のプラグインは設定が必要です。
</p>
<div id="container">
	<div class="plugins-container">
		<h5>使用中のプラグイン</h5>
		<ul class="sortable" id="use">
			<?php
			if (is_array($using)) {
				foreach ($using as $value) {
					echo '<li class="ui-state-default color-red" itemid="' . $value['folder'] . '#' . $value['configid'] . '">';
					showPluginInfo($value);
					echo '</li>';
				}
			}
		?>
</ul>
</div>
<div class="plugins-container">
<h5>インストール済みのプラグイン</h5>
<ul id="notuse">
<?php
foreach ($plugins as $value) {
	echo '<li class="color-blue';
	if ($value['config'] == "" || $value['configid'] != "") {
		echo ' draggable';
	} else {
		echo ' cantdrag';
	}
	if ($value['config'] != "" && $value['configid'] != "") {
		echo ' configured-blue';
	}

	echo '" itemid="' . $value['folder'] . '#' . $value['configid'] . '">';
	showPluginInfo($value);
	echo '</li>';
}
?>
</ul>
</div>
<div class="plugins-container">
<h5>ごみ箱</h5>
<ul class="sortable-notuse" id="notuse"></ul>
</div>
</div>
<button class="btn btn-primary pull-right plugins-submit">
設定を保存
</button>
<script src="./js/jquery-ui-1.10.2.custom.min.js"></script>
<script>
	$(function() {
		$(".sortable").sortable({
			revert : true,
			connectWith : ".sortable-notuse"
		});
		$(".sortable-notuse").sortable({
			revert : true,
			connectWith : ".sortable"
		});
		$(".draggable").draggable({
			connectToSortable : ".plugins-container .sortable",
			helper : function() {
				return $(this).clone().width($(this).width());
			},
			revert : "invalid"
		});
		$("ul, li").disableSelection();
		$(".plugins-submit").click(function() {
			var input = $("#use").sortable("serialize", {
				attribute : "itemid",
				key : "id[]",
				expression : /(.*)/
			});
			$.post("./data/nav/update.php", input, function(res) {
				showAlert(res);
				envReset();
			});
		})
	});

</script>