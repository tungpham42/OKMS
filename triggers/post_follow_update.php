<?php //Update follow post button
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	$post_follow = post_follow_load($pid,$uid);
	print '<script type="text/javascript">
			$("#post_follow_pid_'.$pid.'").html("'.count_post_follows($pid).' Follow'.((count_post_follows($pid) == 0 || count_post_follows($pid) == 1) ? '': 's').'").'.(($post_follow['User_ID'] != $uid) ? 'remove': 'add').'Class("clicked");
			</script>';
}
?>