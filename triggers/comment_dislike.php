<?php //Dislike or undislike comment
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['comid']) && isset($_SESSION['uid'])) {
	$comid = $_POST['comid'];
	$uid = $_SESSION['uid'];
	$vote_sql = mysql_query("SELECT * FROM ".PREFIX."COMMENT_VOTE WHERE Comment_ID='".$comid."' AND User_ID='".$uid."'");
	$votes = array_load_with_two_identifier('COMMENT_VOTE','Comment_ID','"'.$comid.'"','User_ID','"'.$uid.'"');
	sort($votes);
	$vote = $votes[0];
	$count = mysql_num_rows($vote_sql);
	if ($count == 0) {
		$sql_in = "INSERT INTO ".PREFIX."COMMENT_VOTE(User_ID,Comment_ID,CommentVote_Dislike,CommentVote_Like) VALUES('".$uid."','".$comid."','1','0')";
		mysql_query($sql_in);
	} elseif ($count == 1 && $vote['CommentVote_Dislike'] == 1 && $vote['CommentVote_Like'] == 0) {
		mysql_query("UPDATE ".PREFIX."COMMENT_VOTE c SET c.CommentVote_Dislike = 0 WHERE c.Comment_ID='".$comid."' AND c.User_ID='".$uid."'");
	} elseif ($count == 1 && $vote['CommentVote_Dislike'] == 0 && $vote['CommentVote_Like'] == 0) {
		mysql_query("UPDATE ".PREFIX."COMMENT_VOTE c SET c.CommentVote_Dislike = 1 WHERE c.Comment_ID='".$comid."' AND c.User_ID='".$uid."'");
		mysql_query("UPDATE ".PREFIX."COMMENT_VOTE c SET c.CommentVote_Like = 0 WHERE c.Comment_ID='".$comid."' AND c.User_ID='".$uid."'");
	} elseif ($count == 1 && $vote['CommentVote_Dislike'] == 0 && $vote['CommentVote_Like'] == 1) {
		mysql_query("UPDATE ".PREFIX."COMMENT_VOTE c SET c.CommentVote_Dislike = 1 WHERE c.Comment_ID='".$comid."' AND c.User_ID='".$uid."'");
		mysql_query("UPDATE ".PREFIX."COMMENT_VOTE c SET c.CommentVote_Like = 0 WHERE c.Comment_ID='".$comid."' AND c.User_ID='".$uid."'");
	}
}
?>