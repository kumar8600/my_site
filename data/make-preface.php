<?php
require_once dirname(__FILE__) . '/../config.php';

function makePreface($body) {
	// 本文から序文を切り出す
	$length = $GLOBALS['preface_length'];

	return htmlCut($body, $length);
}

function removeIndents($str) {
	return preg_replace('/\s+/', ' ', $str);
}

function getElementName($str) {
	// <うんこ class="unko"> の「うんこ」を返す
	$val = preg_match('/[a-zA-Z1-9]+/', $str, $ret);
	if ($val) {
		return $ret[0];
	}
	return false;
}

function isEndTag($str) {
	$ret = preg_match('/^[^a-zA-Z0-9]*[\/]+/', $str, $ret);
	return $ret;
}

function isNoElementsTag($str) {
	// 要素を持たない（閉じタグの必要のない）タグか判定
	// <unko /> みたいなタイプのタグ。XHTMLでは100割これのつもりらしい
	if (preg_match('/\/[\s>]*$/', $str)) {
		return true;
	}
	// <!DOCTYPE とか
	if (preg_match('/^[\s<]*!/', $str)) {
		return true;
	}
	// その他あるあるネタ
	$empty_elements = array('meta', 'img', 'br', 'input', 'link', 'hr');
	$name = getElementName($str);
	if (in_array($name, $empty_elements)) {
		return true;
	}
	return false;
}

function isIngnoreElementsTag($str) {
	// <style></style>や<script></script>は要素が表示されないタグ。
	$ignore_elements = array('style', 'script');
	if (in_array($str, $ignore_elements)) {
		return true;
	}
	return false;
}

function htmlCut($str, $length) {
	// 空白も一文字としてカウントするので、正確に文字数を反映させるには無駄な空白、改行、タブスペース等は削除しておくこと。
	// おせっかいかもしれないが標準でする。
	$str = removeIndents($str);
	// マルチバイトに対応したhtmlタグを破壊せずに$length文の文字を取り出す関数
	mb_internal_encoding("UTF-8");
	// オフセットの実装はめんどくさいし使わないからやめた
	$offset = 0;
	$pos = $offset;
	// 最終的に$lengthの位置が元の$strでどこなのかを表す
	$count = 0;
	// まず、$length文字文の要素が見つかるまで数える
	while (true) {
		$tag_start = mb_strpos($str, "<", $pos);

		if ($tag_start === false) {
			break;
		}
		$old_count = $count;
		// > と < の間の文字数を数える。
		$count += $tag_start - $pos;
		if ($count >= $length) {
			$pos = $pos + $length - $old_count;
			break;
		}

		$tag_end = mb_strpos($str, ">", $tag_start);

		$buf_start = $tag_start;
		while (true) {
			// タグ内に更にタグが書いてあるか調べ(<unko<tinko></tinko> >みたいな)、そうなら正しい$tag_endを探す
			$buf_start = mb_strpos($str, "<", $buf_start + 1);
			if ($buf_start === false) {
				break;
			}
			if ($buf_start < $tag_end) {
				$tag_end = mb_strpos($str, ">", $tag_end + 1);
			} else {
				break;
			}
		}
		$pos = $tag_end + 1;

		//次のタグが要素を無視すべきタグか調べ、そうなら無視して先へ
		$tag = mb_substr($str, $tag_start, $tag_end - $tag_start + 1);
		$name = getElementName($tag);
		if (isIngnoreElementsTag($name)) {
			echo "uy";
			$tag_start = mb_strpos($str, "<", $pos);
			$tag_end = mb_strpos($str, ">", $tag_start);
			$pos = $tag_end + 1;
		}
	}
	// この時点で、正しい$posを求め終わっている。あとは、$posの時点で閉じられていないタグを探し、閉じタグを追加する。
	$t_pos = 0;
	$el_names = array();
	while (true) {
		$tag_start = mb_strpos($str, "<", $t_pos);
		if ($tag_start === false || $tag_start >= $pos) {
			break;
		}
		$tag_end = mb_strpos($str, ">", $tag_start);
		$buf_start = $tag_start;
		while (true) {
			// タグ内に更にタグが書いてあるか調べ(<unko<tinko></tinko> >みたいな)、そうなら正しい$tag_endを探す
			$buf_start = mb_strpos($str, "<", $buf_start + 1);
			if ($buf_start < $tag_end) {
				$tag_end = mb_strpos($str, ">", $tag_end + 1);
			} else {
				break;
			}
		}
		$tag = mb_substr($str, $tag_start, $tag_end - $tag_start + 1);

		if (!isNoElementsTag($tag)) {
			if (!isEndTag($tag)) {
				array_unshift($el_names, getElementName($tag));
			} else {// もし閉じタグなら
				$key = array_search(getElementName($tag), $el_names);
				array_splice($el_names, $key, 1);
			}
		}

		$t_pos = $tag_end + 1;
	}
	// 上記の処理により、配列$el_nameには閉じられなかったタグの要素名のみが残っているので、あとはそれを閉じタグとして追加する。
	$ret = mb_substr($str, $offset, $pos);
	foreach ($el_names as $value) {
		$ret = $ret . "</" . $value . ">";
	}
	return $ret;
}
?>