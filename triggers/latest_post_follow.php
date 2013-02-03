<?php //Follow or unfollow post
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_SESSION['uid'])) {
	$pid = latest_pid_load();
	$uid = $_SESSION['uid'];
	$follows = array_load_with_two_identifier('POST_FOLLOW','Post_ID','"'.$pid.'"','User_ID','"'.$uid.'"');
	sort($follows);
	$follow = $follows[0];
	$count = count($follows);
	if ($count == 0) {
		follow_post($uid,$pid);
	}
}
?>