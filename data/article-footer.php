<?php
require_once dirname(__FILE__) . '/connect-db.php';
require dirname(__FILE__) . '/thum-social-buttons.php';
$id = $row['id'];
$title = $row['title'];
$name = getSiteName();
?>
<div class="ar-social"><?php showSocialButtons($id, $title, $name) ?></div>
<div class="comments">
<?php
require dirname(__FILE__) . '/comment/list-comments.php';
?>
</div>
<div class="comment-form">
<?php
require dirname(__FILE__) . '/comment/send-comment-form.php';
?>
</div>