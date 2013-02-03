<?php //Update comment section
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	print comments_update($pid);
}
?>