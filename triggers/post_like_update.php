<?php //Update like post button
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	$post_vote = post_vote_load($pid,$uid);
	print '<script type="text/javascript">
			$("#post_like_pid_'.$pid.'").html("'.count_post_likes($pid).' Like'.((count_post_likes($pid) == 0 || count_post_likes($pid) == 1) ? '': 's').'").'.(($post_vote['PostVote_Like'] == 0) ? 'remove': 'add').'Class("clicked");
			</script>';
}
?>