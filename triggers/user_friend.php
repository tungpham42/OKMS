<?php //Friend or unfriend user
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['friend_id']) && isset($_SESSION['uid'])) {
	$uid = $_SESSION['uid'];
	$friend_id = $_POST['friend_id'];
	$friends = $db->array_load_with_two_identifier('FRIEND','User_ID','"'.$uid.'"','Friend_ID','"'.$friend_id.'"');
	sort($friends);
	$friend = $friends[0];
	$count = count($friends);
	if ($count == 0) {
		friend_user($uid,$friend_id);
	} elseif ($count == 1) {
		unfriend_user($uid,$friend_id);
	}
}
?>