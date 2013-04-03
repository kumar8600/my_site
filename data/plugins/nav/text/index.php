<div class="dark">
<?php
require_once dirname(__FILE__) . '/../functions.php';
if($_GET['configid'] == "") {
	die("値が足りません。");
}
require getNavConfigDir() . basename(dirname(__FILE__)). '/' . $_GET['configid'] . '.html';
?>
</div>