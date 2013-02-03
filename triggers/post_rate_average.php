<?php //Show average rating
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	print star_rating_average($pid);
}
?>