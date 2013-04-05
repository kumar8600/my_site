<?php

function showSocialButtons($id, $title, $name) {
	$text = $title . ' : ' . $name;
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
		$pro = "https://";
	} else {
		$pro = "http://";
	}
	$url = $pro . $_SERVER["HTTP_HOST"] . '/?p=' . $id;
	echo '<ul>';
	echo '<li> <a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $url . '" data-text="' . $text . '" data-via="kumar8600" data-count="vertical" data-lang="ja">ツイート</a>';
	echo '</li><li> <iframe src="//www.facebook.com/plugins/like.php?href=' . urlencode($url) . '&amp;send=false&amp;layout=box_count&amp;width=70&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=63" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:70px; height:63px;" allowTransparency="true"></iframe>';
	echo '</li><li> <div class="g-plusone" data-size="tall" data-href="' . $url . '"></div>';
	echo '</li><li> <a href="http://b.hatena.ne.jp/entry/' . $url . '" class="hatena-bookmark-button" data-hatena-bookmark-title="' . $text . '" data-hatena-bookmark-layout="vertical-balloon" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a>';
	echo '</li><li> <a href="http://www.tumblr.com/share/link?url='.urlencode($url).'&name='.urlencode($text).'&description='.urlencode("kumr.netで公開してるCMSでこのサイトは作られています").'" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:81px; height:20px; background:url(\'http://platform.tumblr.com/v1/share_1.png\') top left no-repeat transparent;">Share on Tumblr</a>';
	echo '</li></ul>';
}
?>