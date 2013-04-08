<?php
require_once dirname(__FILE__) . '/connect-db.php';

function showSmallSocialButtons($title) {
	$s_name = getSiteName();
	$url = urlencode("http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	$text = urlencode($title . ' : ' . $s_name);
	$tw_account = "kumar8600";
	echo '<span class="pull-right">';
	echo '<ul class="social">';
	echo '<li><a href="http://twitter.com/share?count=horizontal&amp;original_referer='.$url.'&amp;text='.$text.'&amp;url='.$url.'&amp;via='.$tw_account.'" onclick="window.open(this.href, ‘tweetwindow’, ‘width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1′); return false;"><img src="./img/social/twitter@2x.png" /></a></li>';
	echo '<li><a href="http://www.facebook.com/share.php?u='.$url.'" onclick="window.open(this.href, ‘facebookwindow’, ‘width=550, height=450,personalbar=0,toolbar=0,scrollbars=1,resizable=1′); return false;"><img src="./img/social/facebook@2x.png" /></a></li>';
	echo '<li><div class="g-plusone" data-size="medium" data-annotation="none"></div></li>';
	echo '<li><a href="http://b.hatena.ne.jp/entry/" class="hatena-bookmark-button" data-hatena-bookmark-layout="simple" title="このエントリーをはてなブックマークに追加"><img src="./img/social/hatena_bookmark@2x.png" alt="このエントリーをはてなブックマークに追加" width="40" height="40" style="border: none;" /></a></li>';
	echo '</ul>';
	echo '</span>';
}
?>