<?php
$location = dirname(__FILE__);
require_once ($location . '/../config.php');

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

// 基本的なことしかできないが、コンストラクタとSQL文だけで答えの配列を得られるクラス。ファイル名は固定。config.phpでいじれるようにしたい
/*
class EasySQLite3 {
	public $handle;
	function __construct() {
		open();
	}

	function __destruct() {
		$handle -> close();
	}

	function open() {
		try {
			$handle = new SQLite3($GLOBALS['db_path']);
		} catch(Exception $ex) {
			die('DBとの接続に失敗' . $e -> getTraceAsString());
		}
	}

	function queryFetchArray($sql) {
		$result = $handle -> query($sql);
		$row = array();
		$i = 0;
		while ($res = $result -> fetchArray(SQLITE3_ASSOC)) {
			$row[i] = $res;
			$i++;
		}
		
		return $row;
	}
	
	function escapeString($str) {
		return $handle -> escapeString($str);
	}

}
*/
function resize_image(array $options) {
	// デフォルト値の設定
	$defaults = array('image_path' => null, // 画像ファイルのパス
	'save_path' => null, // 画像を保存するパス
	'max_width' => 120, // 最大の幅
	'max_height' => 120, // 最大の高さ
	'quality' => 90 // PNG、JPEG時のクオリティー
	);
	extract($options + $defaults);
	// 画像の情報を取得
	$size = getimagesize($image_path);

	// ファイルから画像の作成。画像のタイプによって関数を使い分ける

	switch($size[2]) {
		case IMAGETYPE_GIF :
			$image = imagecreatefromgif($image_path);
			break;
		case IMAGETYPE_JPEG :
			$image = imagecreatefromjpeg($image_path);
			break;
		case IMAGETYPE_PNG :
			$image = imagecreatefrompng($image_path);
			break;
		default :
			return false;
	}

	// 指定したサイズ以上のものを縮小

	$width = $size[0];
	$height = $size[1];

	if ($width > $max_width) {
		$height *= $max_width / $width;
		$width = $max_width;
	}

	if ($height > $max_height) {
		$width *= $max_height / $height;
		$height = $max_height;
	}

	// 新規画像の作成
	$new_image = imagecreatetruecolor($width, $height);
	// GIFとPNGの透過情報をあれこれ
	if ($size[2] === IMAGETYPE_GIF || $size[2] === IMAGETYPE_PNG) {
		$index = imagecolortransparent($image);

		if ($index >= 0) {

			$color = imagecolorsforindex($image, $index);
			$alpha = imagecolorallocate($new_image, $color['red'], $color['green'], $color['blue']);
			imagefill($new_image, 0, 0, $alpha);
			imagecolortransparent($new_image, $alpha);

		} else if ($size[2] === IMAGETYPE_PNG) {
			imagealphablending($new_image, false);
			$color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
			imagefill($new_image, 0, 0, $color);
			imagesavealpha($new_image, true);
		}
	}

	// リサンプル

	imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

	// 保存しない場合出力するためにHTTPヘッダを送信

	if (!$save_path) {
		$save_path = null;
		header("Content-Type: {$size['mime']}");
	}

	// 各関数、第二引数がnullの場合は生の画像ストリームが直接出力されます。

	switch($size[2]) {
		case IMAGETYPE_GIF :
			$result = imagegif($new_image, $save_path);
			break;
		case IMAGETYPE_JPEG :
			$result = imagejpeg($new_image, $save_path, $quality);
			break;
		case IMAGETYPE_PNG :
			$result = imagepng($new_image, $save_path, floor($quality * 0.09));
			break;
	}

	// メモリ上の画像データを破棄

	imagedestroy($image);
	imagedestroy($new_image);

	return $result;
}
