<?php //Update comment section
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	echo comments_update($pid);
}
?>