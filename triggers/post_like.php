<?php //Like or unlike post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid']) && isset($_SESSION['uid'])) {
	$pid = $_POST['pid'];
	$uid = $_SESSION['uid'];
	$vote_sql = mysql_query("SELECT * FROM ".$db->db_prefix."POST_VOTE WHERE Post_ID='".$pid."' AND User_ID='".$uid."'");
	$votes = $db->array_load_with_two_identifier('POST_VOTE','Post_ID','"'.$pid.'"','User_ID','"'.$uid.'"');
	sort($votes);
	$vote = $votes[0];
	$count = mysql_num_rows($vote_sql);
	if ($count == 0) {
		$sql_in = "INSERT INTO ".$db->db_prefix."POST_VOTE(User_ID,Post_ID,PostVote_Like,PostVote_Dislike) VALUES('".$uid."','".$pid."','1','0')";
		mysql_query($sql_in);
	} elseif ($count == 1 && $vote['PostVote_Like'] == 1 && $vote['PostVote_Dislike'] == 0) {
		mysql_query("UPDATE ".$db->db_prefix."POST_VOTE p SET p.PostVote_Like = 0 WHERE p.Post_ID='".$pid."' AND p.User_ID='".$uid."'");
	} elseif ($count == 1 && $vote['PostVote_Like'] == 0 && $vote['PostVote_Dislike'] == 0) {
		mysql_query("UPDATE ".$db->db_prefix."POST_VOTE p SET p.PostVote_Like = 1 WHERE p.Post_ID='".$pid."' AND p.User_ID='".$uid."'");
		mysql_query("UPDATE ".$db->db_prefix."POST_VOTE p SET p.PostVote_Dislike = 0 WHERE p.Post_ID='".$pid."' AND p.User_ID='".$uid."'");
	} elseif ($count == 1 && $vote['PostVote_Like'] == 0 && $vote['PostVote_Dislike'] == 1) {
		mysql_query("UPDATE ".$db->db_prefix."POST_VOTE p SET p.PostVote_Like = 1 WHERE p.Post_ID='".$pid."' AND p.User_ID='".$uid."'");
		mysql_query("UPDATE ".$db->db_prefix."POST_VOTE p SET p.PostVote_Dislike = 0 WHERE p.Post_ID='".$pid."' AND p.User_ID='".$uid."'");
	}
}
?>