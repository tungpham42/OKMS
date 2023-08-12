<?php //Archive post
require_once '../includes/functions.inc.php';
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
archive_post($pid);
header('location: /post/archive');
?>