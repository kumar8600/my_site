<?php
require_once dirname(__FILE__) . '/../functions.php';
if($_GET['configid'] == "") {
	die("値が足りません。");
}
?>
<div class="dark">
<?php
echo '<legend>' . getConfigNameById(basename(dirname(__FILE__)), $_GET['configid']) . '</legend>';
require getNavConfigDir() . basename(dirname(__FILE__)). '/' . $_GET['configid'] . '.html';
?>
</div>