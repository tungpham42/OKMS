<?php //Like or unlike post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	post_like($pid,$uid);
}
?>