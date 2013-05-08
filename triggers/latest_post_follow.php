<?php //Follow or unfollow post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_SESSION['uid'])) {
	$pid = latest_pid_load();
	$uid = $_SESSION['uid'];
	latest_post_follow($pid,$uid);
}
?>