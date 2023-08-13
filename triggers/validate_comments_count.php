<?php //Validate ask
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
echo (($uid == 0) ? ' not_loggedin': "").((count_comments($pid) == 0) ? ' no_comment': "");
?>