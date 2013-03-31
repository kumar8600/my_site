<?php
require_once dirname(__FILE__) . '/connect-db.php';
$db = connectSettingsDB();
$sql = "SELECT name, description FROM site WHERE id = 1;";
$result = $db -> query($sql);
$row = $result -> fetchArray();
?>
<a href="./" class="reset">
	<h1>
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