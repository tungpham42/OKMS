<?php //Follow or unfollow post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	$follows = $db->array_load_with_two_identifier('POST_FOLLOW','Post_ID','"'.$pid.'"','User_ID','"'.$uid.'"');
	sort($follows);
	$follow = $follows[0];
	$count = count($follows);
	if ($count == 0) {
		follow_post($uid,$pid);
	} elseif ($count == 1) {
		unfollow_post($uid,$pid);
	}
}
?>