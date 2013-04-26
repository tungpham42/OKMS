<?php //Update like comment button
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['comid']) && isset($_SESSION['uid'])) {
	$comid = $_POST['comid'];
	$uid = $_SESSION['uid'];
	$comment_vote = comment_vote_load($comid,$uid);
	print '<script type="text/javascript">
			$("#comment_like_comid_'.$comid.'").html("'.count_comment_likes($comid).' Like'.((count_comment_likes($comid) == 0 || count_comment_likes($comid) == 1) ? '': 's').'").'.(($comment_vote['CommentVote_Like'] == 0) ? 'remove': 'add').'Class("clicked");
			</script>';
}
?>