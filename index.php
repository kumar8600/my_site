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
	</head>
	<body>
		<div class="container">
			<header class="page-header">
				<?php
				require_once dirname(__FILE__) . '/data/header.php';
				?>
			</header>

			<div class="navbar navbar-inverse admin-menu hide">
				<div class="navbar-inner">
					<a class="brand userid"></a>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-wrench icon-white"></i> <b class="caret"></b> </a>
							<ul class="dropdown-menu">
								<li>
									<a class="ajax" href="?admin=set-user">プロフィール</a>
								</li>
								<li>
									<a class="logout" href=""> ログアウト </a>
								</li>
								<li class="divider root-only"></li>
								<li class="nav-header root-only">
									rootユーザー
								</li>
								<li class="root-only">
									<a class="ajax" href="?admin=list-users">ユーザー管理</a>
								</li>
								<li class="root-only">
									<a class="ajax" href="?admin=set-site">サイト設定</a>
								</li>
								<li class="root-only">
									<a class="ajax" href="?admin=set-nav">ナビゲーションカラム設定</a>
								</li>
							</ul>
						</li>
						<li>
							<span> <a class="btn btn-primary ajax" href="?admin=edit-article"> <i class="icon-pencil icon-white"></i>記事の追加 </a> </span>
						</li>
					</ul>

				</div>
			</div>
			<div class="row">
				
			
				<div class="span9 hide" id="contents">
					<div class="spacer">
							<div class="hide" id="tag-search"></div>
							<div id="thumbs"></div>
					</div>
				</div>
				<div class="span9" id="article"></div>
					<div class="span3" id="nav-fixed">
						<div id="nav">
							<?php
							include dirname(__FILE__) . '/data/nav/show.php';
							?>
						</div>
						<div id="nav-toggle">
							<i class="icon-th-list icon-white"></i>
						</div>
					</div>
				</div>
			
		</div>
		
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

		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
		<footer>
			<?php
			include dirname(__FILE__) . '/data/footer.php';
			?>
		</footer>
		
	</body>

</html>
