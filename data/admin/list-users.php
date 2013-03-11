<?php
require_once dirname(__FILE__) . '/../connect-db.php';

$db = connectAuthDB();

$sql = "SELECT sysid, userid, name, email, website FROM user;";
$result = queryDB($db, $sql);

echo '<table><tr><td>sysid</td><td>userid</td><td>name</td><td>email</td><td>website</td></tr>';
while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
	echo '<tr>';
	foreach($row as $key => $value) {
		echo '<td>';
		echo $value;
		echo '</td>';
	}
	echo '</tr>';
}
echo '</table>';
?>