<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>UNKO</title>
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
				<a href="./" class="reset"><h1> 超うんこなサイト<small>うんこでも気づいたこと書きます</small></h1> </a>
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
							</ul>
						</li>
						<li>
							<button class="btn btn-primary new" href="./data/edit-article.html">
								<i class="icon-pencil icon-white"></i>記事の追加
							</button>
						</li>
					</ul>

				</div>
			</div>
			<div class="visible-phone" id="menu-button">
				<button class="btn" id="menu-toggle">
					メニュー
				</button>
			</div>
			<div class="row">
				<div class="span2" id="menu">
					<ul class="nav nav-list">
						<li class="nav-header">
							タグ
						</li>
					</ul>
					<div id="tags-li"></div>
					<ul class="nav nav-list">
						<li class="divider"></li>
						<li>
							<a href="?p=About" class="ajax">About</a>
						</li>
						<li class="login">
							<a href=""> 管理 </a>
						</li>
						<div id="loginform"></div>

					</ul>
				</div>
				<div class="span9" id="alert-div"></div>
				<div class="span10 hide" id="anim">
					<div class="hide" id="article"></div>
				</div>
				<div class="span10 hide" id="tag-search"></div>
				<div id="thumbs"></div>
			</div>
		</div>

		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>

	</body>

</html>
