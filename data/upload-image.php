<?php
require_once dirname(__FILE__) . '/functions.php';
require_once dirname(__FILE__) . '/../config.php';
date_default_timezone_set($GLOBALS['timezone']);
$filename = basename($_FILES['userfile']['name']);
$dir = 'images/' . date("Y-m-d-H-i-s") . '/';
if (!file_exists($dir))
	mkdir($dir);
$filepath = $dir . $filename;
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $filepath)) {
	chmod($filepath, 0644);
	if (isImage($filepath)) {
		$data = array('filename' => $filepath);
	} else {
		$data = array('error' => 'Is not a image');
	}

} else {
	$data = array('error' => 'Failed to save');
}
echo json_encode($data);

//アップロードした画像をリサイズする

$dotpos = strrpos($filepath, '.');
resizeImage(array('image_path' => __DIR__ . '/' . $filepath, 'save_path' => __DIR__ . '/' . substr($filepath, 0, $dotpos) . 'x320' . substr($filepath, $dotpos), // img/thumbは存在していて且つ書き込み可という前提
'max_width' => 320, 'max_height' => 320, 'aspect_ratio_w' => 1, 'aspect_ratio_h' => 1, 'quality' => 100));
resizeImage(array('image_path' => __DIR__ . '/' . $filepath, 'save_path' => __DIR__ . '/' . substr($filepath, 0, $dotpos) . 'x640' . substr($filepath, $dotpos), // img/thumbは存在していて且つ書き込み可という前提
'max_width' => 640, 'max_height' => 640, 'aspect_ratio_w' => 16, 'aspect_ratio_h' => 9, 'quality' => 100));
resizeImage(array('image_path' => __DIR__ . '/' . $filepath, 'save_path' => __DIR__ . '/' . substr($filepath, 0, $dotpos) . 'x1280' . substr($filepath, $dotpos), // img/thumbは存在していて且つ書き込み可という前提
'max_width' => 1280, 'max_height' => 1280, 'aspect_ratio_w' => 16, 'aspect_ratio_h' => 9, 'quality' => 100));
?>