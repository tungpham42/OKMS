<?php //Update comment section
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	print comments_update($pid);
}
?>