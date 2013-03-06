<?php
require_once '../functions.php';

$filename = basename($_FILES['userfile']['name']);
$filepath = 'images/' . $filename;
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $filepath)) {
	chmod($filepath, 0644);
	$data = array('filename' => $filepath);
} else {
	$data = array('error' => 'Failed to save');
}
echo json_encode($data);

//アップロードした画像をリサイズする

$dotpos = strrpos($filepath, '.');
resize_image(array(
    'image_path' => __DIR__ . '/' . $filepath,
    'save_path' => __DIR__ . '/' . substr($filepath, 0, $dotpos) . 'x320' . substr($filepath, $dotpos), // img/thumbは存在していて且つ書き込み可という前提
    'max_width' => 320,
    'max_height' => 320,
    'quality' => 100
));
resize_image(array(
    'image_path' => __DIR__ . '/' . $filepath,
    'save_path' => __DIR__ . '/' . substr($filepath, 0, $dotpos) . 'x640' . substr($filepath, $dotpos), // img/thumbは存在していて且つ書き込み可という前提
    'max_width' => 640,
    'max_height' => 640,
    'quality' => 100
));
resize_image(array(
    'image_path' => __DIR__ . '/' . $filepath,
    'save_path' => __DIR__ . '/' . substr($filepath, 0, $dotpos) . 'x1280' . substr($filepath, $dotpos), // img/thumbは存在していて且つ書き込み可という前提
    'max_width' => 1280,
    'max_height' => 1280,
    'quality' => 100
));

?>