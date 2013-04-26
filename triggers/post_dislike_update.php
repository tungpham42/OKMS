<?php //Update dislike post button
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	$post_vote = post_vote_load($pid,$uid);
	print '<script type="text/javascript">
			$("#post_dislike_pid_'.$pid.'").html("'.count_post_dislikes($pid).' Dislike'.((count_post_dislikes($pid) == 0 || count_post_dislikes($pid) == 1) ? '': 's').'").'.(($post_vote['PostVote_Dislike'] == 0) ? 'remove': 'add').'Class("clicked");
			</script>';
}
?>