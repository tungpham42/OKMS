<?php //Restore post
require_once '../includes/functions.inc';
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
restore_post($pid);
header('location: ../?p=post');
?>