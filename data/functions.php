<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectDB();

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function isImage($path = "") {
	if (!file_exists($path) || !($type = exif_imagetype($path))) {
		return false;
	}
	return $type;
}

function resizeImage(array $options) {
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

	$width = $size[0];
	$height = $size[1];

	// アスペクト比の設定
	if (isset($options['aspect_ratio_w']) && isset($options['aspect_ratio_h'])) {
		$aspect_ratio_w = $options['aspect_ratio_w'];
		$aspect_ratio_h = $options['aspect_ratio_h'];
	} else {
		$aspect_ratio_w = $width;
		$aspect_ratio_h = $height;
	}

	// アスペクト比を守るべく元の画像から切り出す座標を計算。
	if ($width >= $max_width || $height >= $max_height) {
		if ($width / $height < $aspect_ratio_w / $aspect_ratio_h) {
			$src_w = $width;
			$src_h = $width * $aspect_ratio_h / $aspect_ratio_w;
			$src_x = 0;
			$src_y = $height / 2.0 - $src_h / 2.0;
		} else {
			$src_w = $height * $aspect_ratio_w / $aspect_ratio_h;
			$src_h = $height;
			$src_x = $width / 2.0 - $src_w / 2.0;
			$src_y = 0;
		}
	} else {
		$src_x = 0;
		$src_y = 0;
		$src_w = $width;
		$src_h = $height;
	}

	// 出力サイズを計算

	if ($width >= $max_width) {
		$height = $max_width * $aspect_ratio_h / $aspect_ratio_w;
		$width = $max_width;
	}
	if ($height >= $max_height) {
		$width = $max_height * $aspect_ratio_w / $aspect_ratio_h;
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

	imagecopyresampled($new_image, $image, 0, 0, $src_x, $src_y, $width, $height, $src_w, $src_h);

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
