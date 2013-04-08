<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectSettingsDB();
$sql = "SELECT name, description FROM site WHERE id = 1;";
$result = $db -> query($sql);
$row = $result -> fetchArray();

//$url = urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
$url = urlencode("http://" . $_SERVER["HTTP_HOST"]);
$title = urlencode($row['name']);
$desc = urlencode($row['description']);
$text = urlencode($row['name'] . ' | ' . $row['description'] . ' IS GOD SITE !!!!');
$tw_account = "kumar8600";
?>

<a href="./" class="reset">
	<h1 class="pull-left">
		<span id="site-name">
			<?php
			echo($row['name']);
			?>
		</span>
		<small>
			<?php
			echo($row['description']);
			?>
		</small>
	</h1>
</a>
<span class="pull-right">
	<ul class="social">
	<li><a href="http://twitter.com/share?count=horizontal&amp;original_referer=<?php echo $url; ?>&amp;text=<?php echo $text ?>&amp;url=<?php echo $url; ?>&amp;via=<?php echo $tw_account; ?>" onclick="window.open(this.href, ‘tweetwindow’, ‘width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1′); return false;"><img src="./img/social/twitter@2x.png" /></a></li>
	<li><a href="http://www.facebook.com/share.php?u=<?php echo $url ?>" onclick="window.open(this.href, ‘facebookwindow’, ‘width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1′); return false;"><img src="./img/social/facebook@2x.png" /></a></li>
	<li><div class="g-plusone" data-size="medium" data-annotation="none"></div></li>
	<li><a href="http://b.hatena.ne.jp/entry/<?php echo $url ?>" class="hatena-bookmark-button" data-hatena-bookmark-layout="simple" title="このエントリーをはてなブックマークに追加"><img src="./img/social/hatena_bookmark@2x.png" alt="このエントリーをはてなブックマークに追加" width="40" height="40" style="border: none;" /></a></li>
	<li><a href="https://github.com/kumar8600"><img src="./img/social/github_alt2@2x.png" /></a></li>
	</ul>
</span>