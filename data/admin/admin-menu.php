<div id="admin-menu">
	<div id="admin-basic">
		<a class="brand userid"></a>
		<span> <a class="btn btn-primary ajax" href="?admin=edit-article"><i class="icon-pencil icon-white"></i></a> </span>
		<a href="" class="config-toggle" data-toggle="dropdown"> <i class="icon-wrench icon-white"></i></a>
	</div>
	<div id="admin-config">
		<ul>
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
				<a class="ajax" href="?admin=set-nav">ナビゲーションカラム</a>
			</li>
		</ul>
	</div>
