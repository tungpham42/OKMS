<?php //Activate or deactivate user
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['uid'])) {
	$uid = $_POST['uid'];
	$user = user_load($uid);
	if ($user['User_Status'] == 0) {
		activate_user($uid);
	} elseif ($user['User_Status'] == 1) {
		deactivate_user($uid);
	}
	header('Location: '.$_SERVER['HTTP_REFERER'].'');
}
?>