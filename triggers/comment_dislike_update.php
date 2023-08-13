<?php //Update dislike comment button
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['comid']) && isset($_SESSION['uid'])) {
	$comid = $_POST['comid'];
	$uid = $_SESSION['uid'];
	$comment_vote = comment_vote_load($comid,$uid);
	echo '<script type="text/javascript">
			$("#comment_dislike_comid_'.$comid.'").html("'.count_comment_dislikes($comid).' Dislike'.((count_comment_dislikes($comid) == 0 || count_comment_dislikes($comid) == 1) ? '': 's').'").'.(($comment_vote['CommentVote_Dislike'] == 0) ? 'remove': 'add').'Class("clicked");
			</script>';
}
?>