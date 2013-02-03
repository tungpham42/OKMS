<?php //Update user rating
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	$post_rate = post_rate_load($pid,$uid);
	print star_rating_update($pid);
}
?>