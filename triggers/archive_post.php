<?php //Archive post
require_once '../includes/functions.inc';
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
archive_post($pid);
header('location: ../?p=post/archive');
?>