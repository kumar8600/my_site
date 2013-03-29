<?php
	require_once dirname(__FILE__) . '/session.php';
	
	// DBテーブルを作る。
	require dirname(__FILE__) . '/../create-table.php';
	
	// rootユーザーを作る。rootユーザーとしてセッションログインされる。
	require dirname(__FILE__) . '/add-user.php';
	
	// サイトの設定をする。
	require dirname(__FILE__) . '/set-site.php';
	
?>
<p><a href="../../">設定を完了しました。トップページを開き、CMSの使用を開始します。</a></p>