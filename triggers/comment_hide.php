<?php
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['comid'])) {
	$comid = $_POST['comid'];
	$comment = comment_load($comid);
	if ($comment['Comment_Hide_Name'] == 0) {
		hide_comment($comid);
	} elseif ($comment['Comment_Hide_Name'] == 1) {
		unhide_comment($comid);
	}
}
?>