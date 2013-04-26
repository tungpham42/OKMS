<?php //Rate post difficulty
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid']) && isset($_POST['rate']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$rate = $_POST['rate'];
	$uid = $_SESSION['uid'];
	rate_post($uid,$pid,$rate);
}
?>