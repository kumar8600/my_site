<?php
$_GET = array_map("strip_tags", $_GET);
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title><?php
		require_once dirname(__FILE__) . '/data/connect-db.php';
		$db = connectSettingsDB();
		$sql = "SELECT name, description FROM site WHERE id = 1;";
		$result = $db -> query($sql);
		$row = $result -> fetchArray();
		echo $row['name'];
			?></title>
		<meta name="description" content="<?php echo $row['description']; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
		<!-- 自分で書いたやつ -->
		<link href="css/mystyle.css" rel="stylesheet">
		<script src="js/jquery-1.9.1.min.js"></script>
	</head>
	<body>
		
			<header>
				<?php
				require_once dirname(__FILE__) . '/data/header.php';
				?>
			</header>
<div class="container">
			<div class="row">			
				<div class="span9 hide" id="contents">
					<div class="spacer">
						<div id="thumbs"></div>
					</div>
				</div>
				<div  id="nav-container">
						<div class="span3" id="nav">
							<?php
							include dirname(__FILE__) . '/data/nav/show.php';
							?>
						</div>
				</div>
				<div class="span9 hide" id="article"></div>
			</div>
		</div>
		<div class="ajax hide" href="./" id="home-button"><i class="icon-home icon-white"></i></div>
		<div class="span6" id="fixed-menu">
			<div id="menu-toggle">
				<i class="icon-tags icon-white"></i>
			</div>
			<div class="hide" id="menu">
				<div id="tags-li"></div>
				<a href="?p=About" class="ajax"><span class="label label-info"><i class="icon-info-sign icon-white"></i>About(このサイトについて)</span></a>
				<a href=""><span class="label label-warning login"><i class="icon-user icon-white"></i>ログイン</span></a>
				<div id="loginform" class="dark hide"></div>
			</div>
		</div>
		<div id="alert-container">
			<div class="alert hide" id="alert-div"></div>
		</div>
		<div id="admin-menu-container"></div>
		<footer>
		</footer>
		
		<script src="js/history/scripts/bundled/html4+html5/jquery.history.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
	</body>

</html>
