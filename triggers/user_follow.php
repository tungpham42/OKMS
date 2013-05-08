<?php //Friend or unfriend user
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['followee_id']) && isset($_SESSION['uid'])) {
	$uid = $_SESSION['uid'];
	$followee_id = $_POST['followee_id'];
	user_follow($uid,$followee_id);
}
?>