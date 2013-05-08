<?php //Dislike or undislike comment
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['comid']) && isset($_SESSION['uid'])) {
	$comid = $_POST['comid'];
	$uid = $_SESSION['uid'];
	comment_dislike($comid,$uid);
}
?>